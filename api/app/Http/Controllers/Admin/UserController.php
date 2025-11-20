<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Mail\InstructorApprovedMail;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\InstructorRejectedMail;
use App\Models\NotificationUser;
use App\Events\NotificationCreated;

class UserController extends Controller
{
    /**
     * Danh sách user
     */
    public function index(Request $request)
    {
        // 🔹 Khởi tạo query cơ bản (chỉ lấy student & instructor)
        $query = User::query()
            ->whereIn('role', ['student', 'instructor']);

        // 🔍 Tìm kiếm (search theo name, email, username, expertise)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('expertise', 'like', "%{$search}%");
            });
        }

        // 🎓 Lọc theo role (student hoặc instructor)
        if ($role = $request->input('role')) {
            if (in_array($role, ['student', 'instructor'])) {
                $query->where('role', $role);
            }
        }

        // ⚙️ Lọc theo status (pending / approved / rejected)
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // 📅 Sắp xếp
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // 📦 Phân trang (mặc định 15 bản ghi/trang)
        $perPage = $request->input('per_page', 15);
        $users = $query->paginate($perPage);

        // 🧠 Trả kết quả JSON chuẩn REST
        return response()->json([
            'success' => true,
            'message' => 'Danh sách người dùng',
            'filters' => [
                'role' => $role ?? 'all',
                'status' => $status ?? 'all',
                'search' => $search ?? null,
            ],
            'pagination' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
            'data' => $users->items(),
        ]);
    }

    /**
     * Tạo user mới
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255', // ✅ không bắt buộc
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
     * Hiển thị chi tiết user
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Cập nhật user
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'username' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'role' => ['sometimes', 'required', Rule::in(['student', 'instructor', 'admin'])],
            'status' => ['sometimes', 'required', Rule::in(['pending', 'approved', 'rejected'])],
            'birth_date' => 'nullable|date',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json($user);
    }

    /**
     * Xóa user
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }

    /**
     * Cập nhật profile người dùng đăng nhập
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255', // ✅ bỏ required
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date_format:Y-m-d',
            'gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other'])],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->update($validator->validated());

        return response()->json($user);
    }

    /**
     * Cập nhật avatar
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            if ($user->avatar_url) {
                Storage::disk('public')->delete($user->avatar_url);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_url = $path;
            $user->save();
        }

        return response()->json($user);
    }

    /**
     * Admin phê duyệt người dùng.
     */
    public function approveUser(Request $request, $id)
    {
        // ✅ Chỉ admin mới được duyệt
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Hành động này yêu cầu quyền quản trị viên.'], 403);
        }

        $userToApprove = User::findOrFail($id);

        // ✅ Kiểm tra role trước khi duyệt (chỉ instructor)
        if ($userToApprove->role !== 'instructor') {
            return response()->json(['message' => 'Chỉ có thể phê duyệt người dùng là giảng viên.'], 400);
        }

        // ✅ Cập nhật trạng thái
        $userToApprove->status = 'approved';
        $userToApprove->save();

        $notification = Notification::create([
            'title'      => '🎉 Instructor Application Approved',
            'message'    => "Chúc mừng {$userToApprove->name}, hồ sơ giảng viên của bạn đã được phê duyệt!",
            'type'       => 'instructor_approved',
            'related_id' => $userToApprove->id,
        ]);

        $notification->users()->attach($userToApprove->id, [
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Load bản ghi đầy đủ để broadcast
        $full = NotificationUser::with('notification')
            ->where('notification_id', $notification->id)
            ->where('user_id', $userToApprove->id)
            ->first();

        // 🔥 Broadcast realtime
        broadcast(new \App\Events\NotificationCreated($full))->toOthers();


        // ✅ Gửi email thông báo
        try {
            Mail::to($userToApprove->email)->queue(new InstructorApprovedMail($userToApprove));
        } catch (\Throwable $e) {
            \Log::error("Failed to send instructor approved mail: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Người dùng đã được phê duyệt thành công và email thông báo đã được gửi.',
            'user' => $userToApprove
        ]);
    }

    public function rejectUser(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Hành động này yêu cầu quyền quản trị viên.'], 403);
        }

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $userToReject = User::findOrFail($id);

        if ($userToReject->role !== 'instructor') {
            return response()->json(['message' => 'Chỉ có thể từ chối người dùng là giảng viên.'], 400);
        }

        if ($userToReject->status === 'rejected') {
            return response()->json(['message' => 'Hồ sơ này đã bị từ chối trước đó.'], 400);
        }

        // ✅ Cập nhật trạng thái
        $userToReject->status = 'rejected';
        $userToReject->save();

        // Tạo bản ghi notification
        $notification = Notification::create([
            'title'      => '⚠️ Instructor Application Rejected',
            'message'    => "Rất tiếc, hồ sơ giảng viên của bạn đã bị từ chối." .
                ($request->reason ? " Lý do: {$request->reason}" : ""),
            'type'       => 'instructor_rejected',
            'related_id' => $userToReject->id,
        ]);

        // Tạo bản ghi pivot (notification_user)
        $notification->users()->attach($userToReject->id, [
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Load lại pivot để broadcast realtime
        $pivot = NotificationUser::with('notification')
            ->where('notification_id', $notification->id)
            ->where('user_id', $userToReject->id)
            ->first();

        // Bắn realtime event
        broadcast(new \App\Events\NotificationCreated($pivot))->toOthers();


        // ✅ Gửi email thông báo
        try {
            Mail::to($userToReject->email)->queue(new InstructorRejectedMail($userToReject, $request->reason));
        } catch (\Throwable $e) {
            \Log::error("Failed to send instructor rejection mail: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Hồ sơ giảng viên đã bị từ chối và thông báo đã được gửi.',
            'user' => $userToReject,
        ]);
    }
}
