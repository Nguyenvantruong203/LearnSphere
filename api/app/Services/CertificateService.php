<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseCertificate;
use Illuminate\Support\Str;
use App\Events\CertificateIssued;

class CertificateService
{
    public static function issueCertificate($userId, $courseId)
    {
        // Đã có chứng chỉ → bỏ qua
        if (CourseCertificate::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists()) {
            return;
        }

        $code = 'LS-' . strtoupper(Str::random(8));

        // Lưu vào DB
        $certificate = CourseCertificate::create([
            'user_id'          => $userId,
            'course_id'        => $courseId,
            'certificate_code' => $code,
            'issued_at'        => now(),
        ]);

        // Lấy thông tin giảng viên + khóa học
        $course = Course::find($courseId);
        $instructorId = $course->created_by;

        /**
         * Realtime gửi đến giảng viên
         */
        event(new CertificateIssued(
            instructorId: $instructorId,
            studentId: $userId,
            courseId: $courseId,
            courseTitle: $course->title,
            certificateCode: $code
        ));

        return $certificate;
    }
}
