<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;

class InstructorController extends Controller
{
    /**
     * Danh sách giảng viên được duyệt (phân trang)
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'instructor')
            ->where('status', 'approved')
            ->select('id', 'name', 'avatar_url', 'expertise', 'bio', 'linkedin_url', 'portfolio_url', 'teaching_experience');

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('expertise', 'like', "%{$search}%");
            });
        }

        $instructors = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 9));

        return response()->json([
            'success' => true,
            'data' => $instructors,
        ]);
    }

    /**
     * 📝 Public apply làm giảng viên (không cần đăng nhập)
     */
    public function apply(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'expertise' => 'required|string|max:255',
            'bio' => 'required|string|min:30',
            'linkedin_url' => 'nullable|url',
            'portfolio_url' => 'nullable|url',
            'teaching_experience' => 'nullable|integer|min:0|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // 🔹 Generate password nếu chưa có
        $password = $request->password ?? str()->random(10);

        // 🔹 Tạo hồ sơ instructor (pending + chưa verify email)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($password),
            'role' => 'instructor',
            'status' => 'pending',
            'expertise' => $request->expertise,
            'bio' => $request->bio,
            'linkedin_url' => $request->linkedin_url,
            'portfolio_url' => $request->portfolio_url,
            'teaching_experience' => $request->teaching_experience,
        ]);

        // 📩 Gửi email xác thực (sử dụng cơ chế Laravel)
        event(new Registered($user));

        return response()->json([
            'success' => true,
            'message' => 'Your instructor application has been received! Please check your email inbox to verify your account before we review your application.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status,
            ],
        ]);
    }
}
