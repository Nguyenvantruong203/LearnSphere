<?php

namespace App\Http\Controllers;

use App\Models\NotificationUser;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Lấy notification của user login
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        $query = NotificationUser::with('notification')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at');

        $paginated = $query->paginate($perPage, ['*'], 'page', $page);

        $items = $paginated->map(function ($item) {
            return [
                'id'         => $item->id,
                'is_read'    => $item->is_read,
                'read_at'    => $item->read_at,
                'created_at' => $item->created_at,
                'title'      => $item->notification->title,
                'message'    => $item->notification->message,
                'type'       => $item->notification->type,
                'data'       => $item->notification->data,
            ];
        });

        return response()->json([
            'success' => true,
            'notifications' => $items,
            'pagination' => [
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'per_page' => $paginated->perPage(),
                'last_page' => $paginated->lastPage(),
            ]
        ]);
    }


    /**
     * Đánh dấu 1 thông báo đã đọc
     */
    public function markAsRead($id)
    {
        $user = auth()->user();

        $noti = NotificationUser::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $noti->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Đánh dấu tất cả thông báo đã đọc
     */
    public function markAllAsRead()
    {
        $user = auth()->user();

        NotificationUser::where('user_id', $user->id)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }
}
