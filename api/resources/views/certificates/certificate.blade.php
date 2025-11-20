<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Certificate of Completion - {{ $course->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Georgia', 'DejaVu Sans', serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            width: 297mm;
            height: 210mm;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .certificate-wrapper {
            width: 280mm;
            height: 195mm;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 25px 80px rgba(0,0,0,0.4);
            position: relative;
            overflow: hidden;
            border: 12px double #d4af37;
            background-image:
                linear-gradient(45deg, #f8f9fa 25%, transparent 25%),
                linear-gradient(-45deg, #f8f9fa 25%, transparent 25%),
                linear-gradient(45deg, transparent 75%, #f8f9fa 75%),
                linear-gradient(-45deg, transparent 75%, #f8f9fa 75%);
            background-size: 20px 20px;
            background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
        }

        /* Elegant border pattern */
        .border-pattern {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 2px solid #d4af37;
            pointer-events: none;
            border-radius: 15px;
        }

        /* Subtle background ornament */
        .ornament {
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, #d4af37 1px, transparent 1px);
            background-size: 30px 30px;
            opacity: 0.08;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .header {
            text-align: center;
            padding: 30px 0 20px;
            color: #1e40af;
            position: relative;
        }

        .platform-name {
            font-size: 36px;
            font-weight: 700;
            letter-spacing: 8px;
            text-transform: uppercase;
            color: #1e3a8a;
            margin: 0;
        }

        .platform-subtitle {
            font-size: 18px;
            color: #64748b;
            letter-spacing: 4px;
            margin: 8px 0 0;
            font-style: italic;
        }

        .main-content {
            padding: 20px 60px;
            text-align: center;
        }

        .badge {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #d4af37, #f59e0b);
            border-radius: 50%;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 15px 35px rgba(212, 175, 55, 0.4);
            border: 6px solid #fff;
        }

        .trophy {
            font-size: 60px;
        }

        .certificate-title {
            font-size: 52px;
            font-weight: bold;
            color: #1e293b;
            margin: 0 0 10px;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .certificate-subtitle {
            font-size: 22px;
            color: #64748b;
            font-style: italic;
            margin: 0 0 40px;
        }

        .presented-to {
            font-size: 20px;
            color: #475569;
            margin: 30px 0 10px;
            font-family: 'Brush Script MT', cursive;
            font-style: italic;
        }

        .recipient-name {
            font-size: 50px;
            font-weight: 900;
            color: #1e293b;
            margin: 15px 0 35px;
            background: linear-gradient(90deg, #d4af37, #b89104);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-family: 'Great Vibes', cursive, serif;
            letter-spacing: 1px;
        }

        .completion-statement {
            font-size: 22px;
            color: #475569;
            margin: 0 0 30px;
            line-height: 1.6;
        }

        .course-title {
            font-size: 36px;
            font-weight: bold;
            color: #1e40af;
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.15), rgba(245, 158, 11, 0.1));
            padding: 20px 50px;
            border: 3px solid #d4af37;
            border-radius: 15px;
            display: inline-block;
            margin: 20px 0 50px;
            max-width: 90%;
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.2);
        }

        /* Details */
        .details {
            display: flex;
            justify-content: center;
            gap: 60px;
            margin: 40px 0;
            flex-wrap: wrap;
        }

        .detail-item {
            text-align: center;
            min-width: 180px;
        }

        .detail-label {
            font-size: 14px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 8px;
        }

        .detail-value {
            font-size: 18px;
            font-weight: bold;
            color: #1e293b;
        }

        /* Signatures */
        .signatures {
            display: flex;
            justify-content: space-around;
            margin-top: 50px;
            padding: 0 80px;
        }

        .signature {
            text-align: center;
        }

        .signature-line {
            width: 200px;
            height: 2px;
            background: #94a3b8;
            margin: 20px auto;
        }

        .signature-name {
            font-size: 20px;
            font-weight: bold;
            color: #1e293b;
            font-family: 'Dancing Script', cursive;
        }

        .signature-title {
            font-size: 14px;
            color: #64748b;
            margin-top: 5px;
        }

        /* Seal */
        .seal {
            position: absolute;
            bottom: 40px;
            right: 50px;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, #1e40af, #1e3a8a);
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            border: 5px solid #d4af37;
            box-shadow: 0 10px 30px rgba(30, 64, 175, 0.4);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .verification {
            position: absolute;
            bottom: 30px;
            left: 50px;
            font-size: 11px;
            color: #94a3b8;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="certificate-wrapper">
        <div class="ornament"></div>
        <div class="border-pattern"></div>

        <!-- Header -->
        <div class="header">
            <h1 class="platform-name">LearnSphere</h1>
            <p class="platform-subtitle">Online Learning Platform</p>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="badge">
                <div class="trophy">Trophy</div>
            </div>

            <h1 class="certificate-title">Certificate of Completion</h1>
            <p class="certificate-subtitle">This is to certify that</p>

            <p class="presented-to">This certificate is proudly presented to</p>
            <h2 class="recipient-name">{{ $user->name }}</h2>

            <p class="completion-statement">has successfully completed the course</p>

            <div class="course-title">{{ $course->title }}</div>

            <!-- Details -->
            <div class="details">
                <div class="detail-item">
                    <div class="detail-label">Certificate ID</div>
                    <div class="detail-value">{{ $certificate->certificate_code }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Date of Issue</div>
                    <div class="detail-value">{{ $certificate->issued_at->format('F j, Y') }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Instructor</div>
                    <div class="detail-value">{{ $course->instructor->name ?? 'LearnSphere Team' }}</div>
                </div>
            </div>

            <!-- Signatures -->
            <div class="signatures">
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ $course->instructor->name ?? 'Instructor' }}</div>
                    <div class="signature-title">Course Instructor</div>
                </div>
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-name">LearnSphere</div>
                    <div class="signature-title">Director of Education</div>
                </div>
            </div>
        </div>

        <!-- Official Seal -->
        <div class="seal">
            Official<br>Seal
        </div>

        <!-- Verification -->
        <div class="verification">
            Verify this certificate: learnsphere.com/verify/{{ $certificate->certificate_code }}
        </div>
    </div>

</body>
</html>
