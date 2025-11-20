<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\NewInstructorAppliedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\NotificationUser;
use App\Events\NotificationCreated;

class NotifyAdminWhenInstructorVerified implements ShouldQueue
{
    public function handle(Verified $event): void
    {
        try {
            $user = $event->user;

            Log::info('Verified event received', [
                'user_id' => $user->id,
                'role' => $user->role,
                'status' => $user->status
            ]);

            if ($user->role !== 'instructor' || $user->status !== 'pending') {
                Log::info('User not eligible for admin notification');
                return;
            }

            // 🔹 Kiểm tra đã có notification hay chưa
            $existingNotification = Notification::where('type', 'instructor_apply')
                ->where('related_id', $user->id)
                ->first();

            if ($existingNotification) {
                Log::info('Notification already exists for this instructor', [
                    'notification_id' => $existingNotification->id
                ]);
                return;
            }

            $admins = User::where('role', 'admin')->get();

            if ($admins->isEmpty()) {
                Log::warning('No admin users found');
                return;
            }

            Log::info('Found admins', ['count' => $admins->count()]);

            // 1️⃣ Tạo Notification gốc
            $notification = Notification::create([
                'title' => 'New Instructor Application Verified',
                'message' => "{$user->name} has verified their email and applied to become an instructor.",
                'type' => 'instructor_apply',
                'related_id' => $user->id,
            ]);

            // 2️⃣ Gắn vào các admin
            foreach ($admins as $admin) {
                $notification->users()->attach($admin->id, [
                    'is_read'    => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Log::info('Notification created', ['notification_id' => $notification->id]);

            // 3️⃣ Lấy tất cả pivot để broadcast
            $pivotItems = NotificationUser::with('notification')
                ->where('notification_id', $notification->id)
                ->get();

            // 4️⃣ Bắn realtime cho từng admin
            foreach ($pivotItems as $pivot) {
                broadcast(new \App\Events\NotificationCreated($pivot))->toOthers();
            }

            // 5️⃣ Gửi email
            Mail::to($admins->first()->email)->send(new NewInstructorAppliedMail($user));

            Log::info('Email sent to admin', ['admin_email' => $admins->first()->email]);
        } catch (\Exception $e) {
            Log::error('Failed to notify admin', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }


    public function failed(Verified $event, \Throwable $exception): void
    {
        Log::error('NotifyAdminWhenInstructorVerified failed permanently', [
            'user_id' => $event->user->id ?? null,
            'error' => $exception->getMessage()
        ]);
    }
}
