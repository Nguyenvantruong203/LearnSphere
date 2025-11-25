<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ChatThread;
use App\Models\ChatParticipant;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Truong Admin',
            'username' => 'admin',
            'email' => 'truongn12345678@gmail.com',
            'password' =>'12345678',
            'phone' => '0987654321',
            'address' => '123 Admin Street, District 1, HCMC',
            'birth_date' => '1990-01-01',
            'gender' => 'male',
            'role' => 'admin',
            'status' => 'approved',
            'email_verified_at' => now(),
            'avatar_url' => 'https://images.unsplash.com/photo-1603415526960-f7e0328f38f4?auto=format&fit=facearea&w=256&h=256&q=80',
        ]);

        $instructor = User::create([
            'name' => 'Giang Instructor',
            'username' => 'giangteacher',
            'email' => 'truongcon02112003@gmail.com',
            'password' => '12345678',
            'phone' => '0905123456',
            'address' => '45 Teacher Avenue, District 3, HCMC',
            'birth_date' => '1993-07-20',
            'gender' => 'female',
            'role' => 'instructor',
            'status' => 'approved',
            'email_verified_at' => now(),
            'expertise' => 'Digital Marketing, Personal Branding, Social Media Strategy',
            'bio' => 'Giảng viên với hơn 8 năm kinh nghiệm trong lĩnh vực marketing số. Từng dẫn dắt nhiều chiến dịch viral cho các thương hiệu lớn.',
            'linkedin_url' => 'https://linkedin.com/in/gianginstructor',
            'portfolio_url' => 'https://giangportfolio.com',
            'teaching_experience' => 8,
            'avatar_url' => 'https://images.unsplash.com/photo-1494790108755-2616b612b786?auto=format&fit=facearea&w=256&h=256&q=80',
        ]);

        $thread = ChatThread::create([
            'thread_type' => 'support',
            'is_group' => false,
            'title' => 'Instructor Support',
            'created_by' => $instructor->id,
        ]);

        ChatParticipant::insert([
            [
                'thread_id' => $thread->id,
                'user_id' => $admin->id,
                'role' => 'admin',
                'joined_at' => now(),
            ],
            [
                'thread_id' => $thread->id,
                'user_id' => $instructor->id,
                'role' => 'instructor',
                'joined_at' => now(),
            ],
        ]);
    }
}
