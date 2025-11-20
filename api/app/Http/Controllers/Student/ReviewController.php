<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseReview;
use App\Models\UserCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Get all reviews of a course
     */
    public function index($courseId)
    {
        $reviews = CourseReview::where('course_id', $courseId)
            ->with(['user:id,name,avatar_url'])
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }


    /**
     * Create a review by student
     */
    public function store(Request $request, $courseId)
    {
        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'comment'  => 'nullable|string|max:2000',
        ]);

        $user = Auth::user();

        // Check enrollment
        $isEnrolled = UserCourse::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->exists();

        if (!$isEnrolled) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn chưa tham gia khóa học nên không thể đánh giá.'
            ], 403);
        }

        // Check if already reviewed
        $existing = CourseReview::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã đánh giá khóa học này rồi.'
            ], 409);
        }

        $review = CourseReview::create([
            'course_id' => $courseId,
            'user_id'   => $user->id,
            'rating'    => $request->rating,
            'comment'   => $request->comment,
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
            'rating'   => 'required|integer|min:1|max:5',
            'comment'  => 'nullable|string|max:2000',
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
            'rating' => $request->rating,
            'comment' => $request->comment
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
     * Summary rating for a course
     */
    public function summary($courseId)
    {
        $reviews = CourseReview::where('course_id', $courseId);

        $count = $reviews->count();
        $avg = round($reviews->avg('rating'), 2);

        // count by star
        $starCounts = [];
        for ($i = 1; $i <= 5; $i++) {
            $starCounts[$i] = CourseReview::where('course_id', $courseId)
                ->where('rating', $i)
                ->count();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total_reviews' => $count,
                'average_rating' => $avg,
                'stars' => $starCounts
            ]
        ]);
    }

}
