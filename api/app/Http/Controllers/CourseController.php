<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $courses = Course::query()
            ->with('creator:id,name') // Lấy thông tin người tạo
            ->search($request->query('search'))
            ->latest()
            ->paginate($request->query('limit', 15));

        return response()->json($courses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
            'status' => ['sometimes', Rule::in(['draft', 'published', 'archived'])],
            'visibility' => ['sometimes', Rule::in(['public', 'unlisted', 'private'])],
            'publish_at' => 'nullable|date',
            'price' => 'sometimes|numeric|min:0',
            'level' => ['sometimes', Rule::in(['beginner', 'intermediate', 'advanced'])],
            'language' => 'sometimes|string|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();
        $validatedData['created_by'] = Auth::id();

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('public/thumbnails');
            $validatedData['thumbnail_url'] = Storage::url($path);
        }

        $course = Course::create($validatedData);

        return response()->json($course, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Course $course)
    {
        // Tải thêm các quan hệ nếu cần
        $course->load('creator:id,name');
        return response()->json($course);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Course $course)
    {
        // Laravel không xử lý tốt multipart/form-data với PUT.
        // Frontend sẽ gửi POST với _method="PUT" nhưng để đơn giản, ta có thể tạo 1 route POST riêng.
        // Ở đây, ta sẽ giả định frontend có thể gửi POST đến route update.
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => ['sometimes', Rule::in(['draft', 'published', 'archived'])],
            'visibility' => ['sometimes', Rule::in(['public', 'unlisted', 'private'])],
            'publish_at' => 'nullable|date',
            'price' => 'sometimes|numeric|min:0',
            'level' => ['sometimes', Rule::in(['beginner', 'intermediate', 'advanced'])],
            'language' => 'sometimes|string|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        if ($request->hasFile('thumbnail')) {
            // Xóa ảnh cũ nếu có
            if ($course->thumbnail_url) {
                $oldPath = str_replace('/storage', 'public', $course->thumbnail_url);
                Storage::delete($oldPath);
            }
            // Lưu ảnh mới
            $path = $request->file('thumbnail')->store('public/thumbnails');
            $validatedData['thumbnail_url'] = Storage::url($path);
        }

        $course->update($validatedData);

        return response()->json($course);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return response()->json(null, 204);
    }
}
