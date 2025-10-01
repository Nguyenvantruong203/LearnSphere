<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;  

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query()
            ->with('instructor')
            ->published(); // chỉ hiển thị course đã publish

        // Search
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by subject
        if ($request->has('subject')) {
            $query->where('subject', $request->input('subject'));
        }

        // Filter by paid/free
        if ($request->has('is_paid')) {
            $query->where('is_paid', filter_var($request->input('is_paid'), FILTER_VALIDATE_BOOLEAN));
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Price range
        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }
        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $courses = $query->paginate($request->input('per_page', 15));

        return response()->json($courses);
    }
}
