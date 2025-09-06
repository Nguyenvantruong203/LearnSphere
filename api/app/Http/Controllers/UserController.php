<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('username', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by role
        if ($request->has('role')) {
            $query->where('role', $request->input('role'));
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate($request->input('per_page', 15));

        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'nullable|string|max:50|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20|unique:users',
            'role' => ['required', Rule::in(['student', 'instructor', 'admin'])],
            'status' => ['nullable', Rule::in(['pending', 'approved', 'rejected'])],
            'birth_date' => 'nullable|date',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create($validator->validated());

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes','required','string','email','max:255', Rule::unique('users')->ignore($user->id)],
            'username' => ['nullable','string','max:50', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'phone' => ['nullable','string','max:20', Rule::unique('users')->ignore($user->id)],
            'role' => ['sometimes','required', Rule::in(['student', 'instructor', 'admin'])],
            'status' => ['sometimes','required', Rule::in(['pending', 'approved', 'rejected'])],
            'birth_date' => 'nullable|date',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        // Chỉ cập nhật password nếu nó được cung cấp
        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Thêm policy check ở đây nếu cần, ví dụ:
        // $this->authorize('delete', $user);

        $user->delete();

        return response()->json(null, 204);
    }
}
