<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * ==============================
     * 1) OVERVIEW
     * ==============================
     * Tổng quan hệ thống: tổng user, tổng doanh thu hôm nay, tháng, đơn hàng mới…
     */
    public function overview()
    {
        $today = Carbon::today();
        $month = Carbon::now()->month;

        // Tổng user
        $totalUsers = User::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalInstructors = User::where('role', 'instructor')->count();

        // Doanh thu hôm nay
        $todayRevenue = Order::whereDate('created_at', $today)
            ->where('status', 'paid')
            ->sum('final_price');

        // Doanh thu tháng
        $monthRevenue = Order::whereMonth('created_at', $month)
            ->where('status', 'paid')
            ->sum('final_price');

        // Đơn hàng mới hôm nay
        $newOrdersToday = Order::whereDate('created_at', $today)->count();

        // Khóa học mới tạo trong tháng
        $newCourses = Course::whereMonth('created_at', $month)->count();

        return response()->json([
            'success' => true,
            'total_users' => $totalUsers,
            'total_students' => $totalStudents,
            'total_instructors' => $totalInstructors,
            'today_revenue' => $todayRevenue,
            'month_revenue' => $monthRevenue,
            'new_orders_today' => $newOrdersToday,
            'new_courses' => $newCourses,
        ]);
    }

    /**
     * ==============================
     * 2) Revenue by Month (chart)
     * ==============================
     */
    public function revenueByMonth()
    {
        $rows = Order::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') AS month"),
                DB::raw("SUM(final_price) AS revenue")
            )
            ->where('status', 'paid')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json([
            'success' => true,
            'months' => $rows->pluck('month'),
            'values' => $rows->pluck('revenue'),
        ]);
    }

    /**
     * ==============================
     * 3) Top Selling Courses
     * ==============================
     */
    public function topCourses()
    {
        $rows = OrderItem::select(
                'courses.title',
                DB::raw("SUM(order_items.final_price_per_item) AS revenue")
            )
            ->join('courses', 'courses.id', '=', 'order_items.course_id')
            ->groupBy('order_items.course_id', 'courses.title')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'courses' => $rows,
        ]);
    }

    /**
     * ==============================
     * 4) User Statistics (pie chart)
     * ==============================
     */
    public function userStats()
    {
        $students = User::where('role', 'student')->count();
        $instructors = User::where('role', 'instructor')->count();
        $admins = User::where('role', 'admin')->count();

        return response()->json([
            'success' => true,
            'stats' => [
                ['value' => $students, 'name' => 'Students'],
                ['value' => $instructors, 'name' => 'Instructors'],
                ['value' => $admins, 'name' => 'Admins'],
            ]
        ]);
    }
    /**
     * ==============================
     * 7) Chat Statistics (User Support & Instructor Support)
     * ==============================
     */
    public function chatStats()
    {
        // Tổng số tin nhắn từ User → Admin (tất cả student messages)
        $studentMessages = DB::table('chat_messages')
            ->join('users', 'users.id', '=', 'chat_messages.sender_id')
            ->where('users.role', 'student')
            ->count();

        // Tổng số tin nhắn từ Instructor → Admin
        $instructorMessages = DB::table('chat_messages')
            ->join('users', 'users.id', '=', 'chat_messages.sender_id')
            ->where('users.role', 'instructor')
            ->count();

        // Tin nhắn chưa đọc (giả sử admin có ID = 1)
        $unreadMessages = DB::table('chat_messages')
            ->join('chat_participants', 'chat_participants.thread_id', '=', 'chat_messages.thread_id')
            ->where('chat_participants.user_id', 1) // Admin ID
            ->whereNull('chat_participants.last_read_at')
            ->orWhereColumn('chat_messages.sent_at', '>', 'chat_participants.last_read_at')
            ->count();

        // Số cuộc hội thoại active gần đây
        $activeThreads = DB::table('chat_threads')
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->count();

        return response()->json([
            'success' => true,
            'stats' => [
                'student_messages' => $studentMessages,
                'instructor_messages' => $instructorMessages,
                'unread_messages' => $unreadMessages,
                'active_threads' => $activeThreads,
            ]
        ]);
    }

    /**
     * ==============================
     * 8) System Health & Performance
     * ==============================
     */
    public function systemHealth()
    {
        // Tổng khóa học đã được phê duyệt
        $approvedCourses = Course::where('status', 'approved')->count();

        // Tổng khóa học đang chờ phê duyệt
        $pendingCourses = Course::where('status', 'pending')->count();

        // Tổng đơn hàng thành công
        $successfulOrders = Order::where('status', 'paid')->count();

        // Tổng quiz đã được tạo
        $totalQuizzes = DB::table('quizzes')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'approved_courses' => $approvedCourses,
                'pending_courses' => $pendingCourses,
                'successful_orders' => $successfulOrders,
                'total_quizzes' => $totalQuizzes,
            ]
        ]);
    }
}
