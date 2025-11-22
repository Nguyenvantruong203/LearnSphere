<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CourseReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Lấy danh sách review của khóa học
     */
    public function index(Request $request, $courseId)
    {
        $limit = $request->get('limit', 10); // mặc định 10
        $reviews = CourseReview::where('course_id', $courseId)
            ->with(['user:id,name,avatar_url'])
            ->orderByDesc('created_at')
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }


    public function canReview($courseId)
    {
        $user = Auth::user();

        // 1. Kiểm tra đã học + đã thanh toán
        $isEnrolled = $user->enrolledCourses()
            ->where('course_id', $courseId)
            ->wherePivot('is_paid', true)
            ->exists();

        if (!$isEnrolled) {
            return response()->json([
                'can_review' => false,
                'reason' => 'not_enrolled'
            ]);
        }

        // 2. Kiểm tra đã review chưa
        $exists = CourseReview::where('course_id', $courseId)
            ->where('user_id', $user->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'can_review' => false,
                'reason' => 'already_reviewed'
            ]);
        }

        return response()->json([
            'can_review' => true,
            'reason' => null
        ]);
    }


    /**
     * Tạo review
     */
    public function store(Request $request, $courseId)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        $user = Auth::user();

        // Kiểm tra học viên đã đăng ký khóa học chưa
        $isEnrolled = $user->enrolledCourses()
            ->wherePivot('is_paid', true)
            ->where('course_id', $courseId)
            ->exists();

        if (!$isEnrolled) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn chưa tham gia khóa học nên không thể đánh giá.'
            ], 403);
        }

        // Kiểm tra đã review chưa
        $existing = CourseReview::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã đánh giá khóa học này rồi.'
            ], 409);
        }

        // Tạo review
        $review = CourseReview::create([
            'course_id'  => $courseId,
            'user_id'    => $user->id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $review
        ]);
    }



    /**
     * Update review
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        $user = Auth::user();

        $review = CourseReview::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đánh giá hoặc bạn không có quyền sửa.'
            ], 404);
        }

        $review->update([
            'rating'  => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'data' => $review
        ]);
    }


    /**
     * Delete review
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $review = CourseReview::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đánh giá hoặc bạn không có quyền xóa.'
            ], 404);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa đánh giá thành công.'
        ]);
    }

    /**
     * Tổng hợp rating của khóa học
     */
    public function summary($courseId)
    {
        $reviews = CourseReview::where('course_id', $courseId);

        $count = $reviews->count();
        $avg   = round($reviews->avg('rating'), 2);

        // Thống kê số lượng theo từng sao
        $starCounts = [];
        for ($i = 1; $i <= 5; $i++) {
            $starCounts[$i] = CourseReview::where('course_id', $courseId)
                ->where('rating', $i)
                ->count();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total_reviews'   => $count,
                'average_rating'  => $avg,
                'stars'           => $starCounts,
            ]
        ]);
    }
}
