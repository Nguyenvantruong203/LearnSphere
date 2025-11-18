<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

/**
 * Author: Truong
 * Course approval controller for admin
 */
class CourseController extends Controller
{
    /**
     * Lấy danh sách khóa học cho admin duyệt
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'page' => 'integer|min:1',
            'limit' => 'integer|min:1|max:100',
            'search' => 'string|max:255',
            'status' => Rule::in(['pending', 'approved', 'rejected', 'draft', 'archived'])
        ]);

        $query = Course::with(['instructor:id,name,email,role'])
            ->withCount(['topics', 'lessons']);

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function (Builder $q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('subject', 'LIKE', "%{$search}%")
                    ->orWhereHas('instructor', function (Builder $instructorQuery) use ($search) {
                        $instructorQuery->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sắp xếp mặc định: pending trước, sau đó theo created_at desc
        $query->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected', 'draft', 'archived')")
            ->orderBy('created_at', 'desc');

        // Phân trang
        $limit = $request->get('limit', 10);
        $courses = $query->paginate($limit);

        // Format dữ liệu trả về
        $formattedCourses = $courses->items();
        foreach ($formattedCourses as $course) {
            $course->total_topics = $course->topics_count ?? 0;
            $course->total_lessons = $course->lessons_count ?? 0;
            unset($course->topics_count, $course->lessons_count);
        }

        return response()->json([
            'data' => $formattedCourses,
            'total' => $courses->total(),
            'current_page' => $courses->currentPage(),
            'last_page' => $courses->lastPage(),
            'per_page' => $courses->perPage(),
        ]);
    }

    /**
     * Lấy chi tiết một khóa học
     */
    public function show(string $id): JsonResponse
    {
        $course = Course::with([
            'instructor:id,name,email,role,phone,bio,expertise',
            'topics:id,course_id,title,order',
            'lessons:id,topic_id,title,created_at'
        ])
            ->withCount(['topics', 'lessons'])
            ->findOrFail($id);

        // Format dữ liệu
        $course->total_topics = $course->topics_count ?? 0;
        $course->total_lessons = $course->lessons_count ?? 0;
        unset($course->topics_count, $course->lessons_count);

        return response()->json($course);
    }

    /**
     * Phê duyệt khóa học
     */
    public function approve(string $id): JsonResponse
    {
        $course = Course::with('instructor')->findOrFail($id);

        // Chỉ được duyệt khi đang pending
        if ($course->status !== 'pending') {
            return response()->json([
                'error' => 'COURSE_NOT_PENDING',
                'message' => 'Chỉ có thể phê duyệt khóa học đang ở trạng thái chờ duyệt.'
            ], 400);
        }

        // Cập nhật trạng thái
        $course->update([
            'status'     => 'approved',
            'publish_at' => now()
        ]);

        // 🔔 Notification cho giảng viên
        Notification::create([
            'type'    => 'course',
            'title'   => 'Khóa học đã được phê duyệt',
            'message' => "Khóa học **{$course->title}** của bạn đã được admin phê duyệt.",
            'data'    => json_encode([
                'course_id' => $course->id,
                'status'    => 'approved',
            ]),
        ])->users()->attach([$course->instructor->id]);

        // Log
        Log::info('Course approved', [
            'course_id'        => $course->id,
            'course_title'     => $course->title,
            'instructor_id'    => $course->instructor->id,
            'instructor_name'  => $course->instructor->name,
            'approved_by'      => Auth::id(),
            'approved_by_name' => Auth::user()->name,
        ]);

        return response()->json([
            'message' => 'Khóa học đã được phê duyệt thành công.',
            'course'  => $course->fresh(),
        ]);
    }


    /**
     * Từ chối khóa học
     */
    public function reject(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'reason' => 'required|string|max:1000|min:10'
        ]);

        $course = Course::with('instructor')->findOrFail($id);

        if ($course->status !== 'pending') {
            return response()->json([
                'error' => 'COURSE_NOT_PENDING',
                'message' => 'Chỉ có thể từ chối khóa học đang ở trạng thái chờ duyệt.'
            ], 400);
        }

        // Cập nhật trạng thái + lý do
        $course->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at'      => now()
        ]);

        // 🔔 Notification cho giảng viên
        Notification::create([
            'type'    => 'course',
            'title'   => 'Khóa học bị từ chối',
            'message' => "Khóa học **{$course->title}** đã bị từ chối.",
            'data'    => json_encode([
                'course_id' => $course->id,
                'status'    => 'rejected',
                'reason'    => $request->rejection_reason,
            ]),
        ])->users()->attach([$course->instructor->id]);

        // Log
        Log::info('Course rejected', [
            'course_id'        => $course->id,
            'course_title'     => $course->title,
            'instructor_id'    => $course->instructor->id,
            'instructor_name'  => $course->instructor->name,
            'reason'           => $request->rejection_reason,
            'rejected_by'      => Auth::id(),
            'rejected_by_name' => Auth::user()->name
        ]);

        return response()->json([
            'message' => 'Khóa học đã bị từ chối.',
            'course'  => $course->fresh(),
        ]);
    }
}
