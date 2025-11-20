<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CourseCertificate;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    /**
     * Lấy toàn bộ chứng chỉ của user đang đăng nhập
     */
    public function listByUser()
    {
        $user = auth()->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $certificates = CourseCertificate::with('course')
            ->where('user_id', $user->id)
            ->orderByDesc('issued_at')
            ->get();

        return response()->json([
            'success'      => true,
            'user_id'      => $user->id,
            'certificates' => $certificates
        ]);
    }

    public function getCertificationDetail($id)
    {
        $user = auth()->user();

        $certificate = CourseCertificate::with(['course', 'user', 'course.instructor'])
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'certificate' => [
                'id' => $certificate->id,
                'certificate_code' => $certificate->certificate_code,
                'issued_at' => $certificate->issued_at,
                'course' => [
                    'id' => $certificate->course->id,
                    'title' => $certificate->course->title,
                ],
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                ],
                'instructor' => [
                    'name' => $certificate->course->instructor->name ?? null
                ]
            ]
        ]);
    }

    /**
     * Lấy chứng chỉ theo course_id
     * Dùng tại trang Learning để hiện popup "Nhận chứng chỉ"
     */
    public function getByCourse($courseId)
    {
        $user = auth()->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        if (! $courseId) {
            return response()->json([
                'success' => false,
                'message' => 'Missing course_id'
            ], 400);
        }

        // Load certificate with course + instructor + user
        $certificate = CourseCertificate::with([
            'course.instructor',
            'user',
        ])
            ->where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->first();

        if (! $certificate) {
            return response()->json([
                'success' => true,
                'certificate' => null
            ]);
        }

        return response()->json([
            'success' => true,
            'certificate' => [
                'id'               => $certificate->id,
                'certificate_code' => $certificate->certificate_code,
                'issued_at'        => $certificate->issued_at,

                'course' => [
                    'id'    => $certificate->course->id,
                    'title' => $certificate->course->title,
                ],

                'user' => [
                    'id'   => $certificate->user->id,
                    'name' => $certificate->user->name,
                ],

                'instructor' => $certificate->course->instructor ? [
                    'id'   => $certificate->course->instructor->id,
                    'name' => $certificate->course->instructor->name,
                ] : null
            ]
        ]);
    }

    /**
     * Tải file PDF của chứng chỉ
     */
    public function download($certificateId)
    {
        $user = auth()->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $certificate = CourseCertificate::with(['user', 'course'])
            ->where('id', $certificateId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Render Blade → PDF
        $pdf = Pdf::loadView('certificates.certificate', [
            'certificate' => $certificate,
            'user'        => $certificate->user,
            'course'      => $certificate->course,
        ]);

        $fileName = 'certificate-' . $certificate->certificate_code . '.pdf';

        return $pdf->download($fileName);
    }
}
