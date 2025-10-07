<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\UserCourse;

class CourseController extends Controller
{
    /**
     * Truy vấn mặc định cho tất cả các hàm trong controller
     */
    protected function baseQuery()
    {
        return Course::query()
            ->with(['instructor:id,name,email,avatar_url'])
            ->withCount(['topics as total_topics', 'lessons as total_lessons'])
            ->published(); // chỉ hiển thị khóa học đã publish
    }

    /**
     * Danh sách khóa học (có filter, search, sort)
     */
    public function index(Request $request)
    {
        $query = $this->baseQuery();

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by subject
        if ($subject = $request->input('subject')) {
            $query->where('subject', $subject);
        }

        // Filter by paid/free
        if (!is_null($request->input('is_paid'))) {
            $query->where('is_paid', filter_var($request->input('is_paid'), FILTER_VALIDATE_BOOLEAN));
        }

        // Filter by category
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        // Price range
        if ($min = $request->input('price_min')) {
            $query->where('price', '>=', $min);
        }
        if ($max = $request->input('price_max')) {
            $query->where('price', '<=', $max);
        }

        // Sorting
        $query->orderBy(
            $request->input('sort_by', 'created_at'),
            $request->input('sort_order', 'desc')
        );

        // Pagination
        $courses = $query->paginate($request->input('per_page', 15));

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
            ->where('is_paid', true)
            ->exists();

        return response()->json([
            'success' => true,
            'hasAccess' => $hasAccess
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
}
