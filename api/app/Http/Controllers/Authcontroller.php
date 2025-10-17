<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Laravel\Socialite\Facades\Socialite;
use Exception;

/**
 * @author Truong
 */
class AuthController extends Controller
{
    /**
     * Đăng ký người dùng mới.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // Mutator sẽ hash mật khẩu
            'role' => 'student', // Mặc định là student
            'status' => 'approved',
        ]);

        event(new Registered($user));

        return response()->json([
            'message' => 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản và chờ quản trị viên phê duyệt.'
        ], 201);
    }

    /**
     * Đăng nhập người dùng.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Email hoặc mật khẩu không chính xác.'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        if (is_null($user->email_verified_at)) {
            return response()->json(['message' => 'Vui lòng xác thực email của bạn trước khi đăng nhập.'], 403);
        }

        if ($user->status !== 'approved') {
            return response()->json(['message' => 'Tài khoản của bạn đang chờ phê duyệt hoặc đã bị từ chối.'], 403);
        }
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Đăng nhập thành công!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    /**
     * Lấy thông tin người dùng đang đăng nhập.
     */
    public function user(Request $request)
    {
        return $request->user();
    }

    /**
     * Đăng xuất người dùng.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Đăng xuất thành công!']);
    }

    /**
     * Gửi email reset mật khẩu.
     * (Chức năng này cần cấu hình mail server)
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Chức năng này sẽ được triển khai sau khi cấu hình mail
        return response()->json(['message' => 'Chức năng reset mật khẩu sẽ sớm được cập nhật.']);
    }

    /**
     * Reset mật khẩu.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Chức năng này sẽ được triển khai sau
        return response()->json(['message' => 'Chức năng reset mật khẩu sẽ sớm được cập nhật.']);
    }

    /**
     * Xác thực địa chỉ email của người dùng và chuyển hướng.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyEmail(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Kiểm tra hash
        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            // Chuyển hướng đến một URL frontend với thông báo lỗi
            return redirect(env('FRONTEND_URL', 'http://localhost:5173') . '/auth/verification-result?status=failed&reason=invalid_link');
        }

        // Kiểm tra nếu đã xác thực
        if ($user->hasVerifiedEmail()) {
            // Chuyển hướng đến trang đăng nhập với thông báo email đã được xác thực
            return redirect(env('FRONTEND_URL', 'http://localhost:5173') . '/login?verified=already');
        }

        // Đánh dấu đã xác thực
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // Chuyển hướng đến trang đăng nhập với thông báo thành công và chờ duyệt
        return redirect(env('FRONTEND_URL', 'http://localhost:5173') . '/login?verified=success');
    }

    /**
     * Gửi lại email xác thực.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email đã được xác thực từ trước.'], 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Một liên kết xác thực mới đã được gửi đến địa chỉ email của bạn.']);
    }

    /**
     * Admin phê duyệt người dùng.
     */
    public function approveUser(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Hành động này yêu cầu quyền quản trị viên.'], 403);
        }

        $userToApprove = User::findOrFail($id);
        $userToApprove->status = 'approved';
        $userToApprove->save();

        // Optional: Gửi email thông báo cho người dùng đã được duyệt
        // $userToApprove->notify(new AccountApprovedNotification());

        return response()->json(['message' => 'Người dùng đã được phê duyệt thành công.', 'user' => $userToApprove]);
    }

    /**
     * Chuyển hướng người dùng đến trang xác thực của Google.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToGoogle()
    {
        // API sẽ trả về URL để frontend redirect
        return response()->json([
            'url' => Socialite::driver('google')
                ->stateless()
                ->with(['prompt' => 'select_account'])
                ->redirect()
                ->getTargetUrl(),
        ]);
    }

    /**
     * Lấy thông tin người dùng từ Google và xử lý đăng nhập/đăng ký.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            if (!$request->has('code')) {
                return response()->json(['message' => 'Google callback không hợp lệ, thiếu authorization code.'], 400);
            }

            $googleUser = Socialite::driver('google')->stateless()->user();

            // Tìm người dùng bằng google_id, nếu không thấy thì tìm bằng email
            $user = User::where('google_id', $googleUser->id)->orWhere('email', $googleUser->email)->first();

            if ($user) {
                // Nếu tìm thấy, cập nhật thông tin từ Google
                $user->update([
                    'name' => $googleUser->name,
                    'username' => $googleUser->username,
                    'google_id' => $googleUser->id,
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'avatar_url' => $user->avatar_url ?? $googleUser->avatar,
                ]);
            } else {
                // Nếu không tìm thấy, tạo người dùng mới
                $user = User::create([
                    'name' => $googleUser->name,
                    'username' => $googleUser->username,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'email_verified_at' => now(),
                    'avatar_url' => $googleUser->avatar,
                    'status' => 'approved',
                    'role' => 'student',
                    'password' => null,
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Đăng nhập bằng Google thành công!',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ]);

        } catch (Exception $e) {
            return response()->json(['message' => 'Xác thực với Google thất bại.', 'error' => $e->getMessage()], 500);
        }
    }
}
