<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\UserCourse;
use App\Models\ChatThread;
use App\Models\ChatParticipant;
use App\Models\Notification;
use App\Models\NotificationUser;
use App\Events\NotificationCreated;

class CourseController extends Controller
{
    /**
     * Truy vấn mặc định cho tất cả các hàm trong controller
     */
    protected function baseQuery()
    {
        return Course::query()
            ->with([
                'instructor:id,name,email,avatar_url,expertise,teaching_experience',
            ])
            ->withCount([
                'topics as total_topics',
                'lessons as total_lessons',
            ])
            ->withAvg('reviews as average_rating', 'rating')
            ->addSelect([
                'total_courses' => Course::selectRaw('COUNT(*)')
                    ->whereColumn('created_by', 'courses.created_by')
                    ->where('status', 'approved')
            ]);
    }

    /**
     * Danh sách khóa học (có filter, search, sort)
     */
    public function index(Request $request)
    {
        $query = $this->baseQuery();

        // 🔍 Search theo tiêu đề hoặc mô tả
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 📚 Lọc theo chủ đề (subject)
        if ($request->filled('subject')) {
            $query->where('subject', $request->input('subject'));
        }

        // 🎓 Lọc theo trình độ (level)
        if ($request->filled('level')) {
            $query->where('level', $request->input('level'));
        }

        // 🗣️ Lọc theo ngôn ngữ (language)
        if ($request->filled('language')) {
            $query->where('language', $request->input('language'));
        }

        // 💰 Lọc theo học phí (miễn phí / trả phí)
        if ($request->has('is_paid')) {
            $isPaid = filter_var($request->input('is_paid'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($isPaid === true) {
                // Khóa học có giá > 0
                $query->where('price', '>', 0);
            } elseif ($isPaid === false) {
                // Khóa học miễn phí
                $query->where(function ($q) {
                    $q->whereNull('price')->orWhere('price', '=', 0);
                });
            }
        }

        // 🏷️ Lọc theo category (nếu có)
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // 💵 Lọc theo khoảng giá
        if ($request->filled('price_min')) {
            $query->where('price', '>=', (float) $request->input('price_min'));
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', (float) $request->input('price_max'));
        }

        // 🔄 Sắp xếp (mặc định: mới nhất)
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->input('per_page', 10);
        $courses = $query->paginate($perPage);

        return response()->json($courses);
    }

    /**
     * Chi tiết khóa học
     */
    public function show($id)
    {
        $course = $this->baseQuery()->find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Khóa học không tồn tại hoặc chưa được publish.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $course
        ]);
    }

    /**
     * Kiểm tra user có quyền truy cập khóa học hay chưa (đã mua)
     */
    public function checkAccess($courseId)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => true,
                'hasAccess' => false
            ]);
        }

        $hasAccess = UserCourse::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->exists();

        return response()->json([
            'success' => true,
            'hasAccess' => $hasAccess
        ]);
    }

    public function enroll($courseId)
    {
        $user = auth()->user();
        $course = Course::with('instructor')->findOrFail($courseId);

        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập trước khi tham gia.'], 401);
        }

        // ✅ 1. Ghi danh học viên
        $enrollment = UserCourse::firstOrCreate(
            ['user_id' => $user->id, 'course_id' => $courseId],
            ['is_paid' => true, 'enrolled_at' => now()]
        );

        $instructorId = $course->created_by ?? $course->instructor_id;

        // ✅ 2. Tạo hoặc tìm thread nhóm của khóa học
        $groupThread = ChatThread::firstOrCreate(
            [
                'course_id'   => $course->id,
                'thread_type' => 'course_group',
            ],
            [
                'is_group'   => true,
                'title'      => "Thảo luận: {$course->title}",
                'created_by' => $instructorId,
            ]
        );

        ChatParticipant::firstOrCreate(
            ['thread_id' => $groupThread->id, 'user_id' => $user->id],
            ['role' => 'student', 'joined_at' => now()]
        );

        // ✅ 3. Nếu có thread "consult" → chuyển thành "private"
        $consultThread = ChatThread::where([
            'course_id' => $course->id,
            'thread_type' => 'consult',
            'created_by' => $user->id,
        ])->first();

        if ($consultThread) {
            $consultThread->update([
                'thread_type' => 'private',
                'title'       => "Trao đổi với giảng viên {$course->instructor->name}",
            ]);

            // Đảm bảo instructor và student đều tham gia
            $consultThread->participants()->syncWithoutDetaching([
                $user->id => ['role' => 'student'],
                $instructorId => ['role' => 'instructor'],
            ]);

            $privateThread = $consultThread;
        } else {
            // ✅ Nếu chưa từng chat consult, tạo mới thread private
            $privateThread = ChatThread::firstOrCreate(
                [
                    'course_id'   => $course->id,
                    'thread_type' => 'private',
                    'is_group'    => false,
                ],
                [
                    'title'      => "Trao đổi với giảng viên {$course->instructor->name}",
                    'created_by' => $user->id,
                ]
            );

            // Gắn hai người vào thread
            $privateThread->participants()->syncWithoutDetaching([
                $user->id => ['role' => 'student'],
                $instructorId => ['role' => 'instructor'],
            ]);
        }

        // ✅ 4. Gửi thông báo cho giảng viên
        if ($instructorId) {

            // 1. Tạo notification
            $noti = Notification::create([
                'type'    => 'course',
                'title'   => 'Học viên mới tham gia khóa học',
                'message' => "{$user->name} vừa ghi danh vào khóa học {$course->title}.",
                'data'    => [
                    'course_id' => $course->id,
                    'user_id'   => $user->id,
                ],
            ]);

            // 2. Attach đến giảng viên
            $noti->users()->attach($instructorId);

            // 3. Lấy pivot vừa tạo
            $pivot = NotificationUser::with('notification')
                ->where('notification_id', $noti->id)
                ->where('user_id', $instructorId)
                ->first();

            // 4. Bắn realtime cho giảng viên
            if ($pivot) {
                broadcast(new \App\Events\NotificationCreated($pivot))->toOthers();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Tham gia khóa học thành công!',
            'data' => [
                'course_id' => $course->id,
                'threads' => [
                    'group'   => $groupThread->id,
                    'private' => $privateThread->id,
                ]
            ],
        ]);
    }

    /**
     * Danh sách khóa học user đã mua
     */
    public function myCourses()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để xem danh sách khóa học đã mua.'
            ], 401);
        }

        $courses = Course::query()
            ->with(['instructor:id,name,avatar_url'])
            ->join('user_courses', 'user_courses.course_id', '=', 'courses.id')
            ->leftJoin('user_progress', function ($join) use ($user) {
                $join->on('user_progress.course_id', '=', 'courses.id')
                    ->where('user_progress.user_id', '=', $user->id);
            })
            ->where('user_courses.user_id', $user->id)
            ->where('user_courses.is_paid', true)
            ->select([
                'courses.*',
                'user_progress.progress_percent as progress',
                'user_progress.last_updated as progress_updated_at',
            ])
            ->orderBy('courses.created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }

    public function recommended()
    {
        $courses = Course::where('status', 'approved')
            ->where('is_featured', true)
            ->orderByDesc('publish_at')
            ->take(8)
            ->get(['id', 'title', 'slug', 'thumbnail_url', 'short_description', 'price', 'subject']);

        return response()->json([
            'status' => 'success',
            'data' => $courses
        ]);
    }
}
