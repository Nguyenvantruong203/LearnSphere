<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo đăng ký khóa học mới</title>
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f4f4;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .header .subtitle {
            font-size: 14px;
            opacity: 0.9;
        }

        .content {
            padding: 30px 20px;
        }

        .greeting {
            font-size: 18px;
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .course-info {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }

        .course-title {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .course-subtitle {
            color: #7f8c8d;
            font-size: 14px;
        }

        .order-details {
            margin: 25px 0;
        }

        .order-details h3 {
            color: #2c3e50;
            font-size: 16px;
            margin-bottom: 15px;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 8px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }

        .info-item {
            background-color: #ffffff;
            border: 1px solid #e9ecef;
            padding: 15px;
            border-radius: 8px;
        }

        .info-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
        }

        .action-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 25px 0;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 12px;
        }

        .footer-logo {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #ddd, transparent);
            margin: 20px 0;
        }

        .highlight-number {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Responsive design */
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0;
                box-shadow: none;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .header {
                padding: 20px 15px;
            }

            .content {
                padding: 20px 15px;
            }

            .course-info {
                padding: 15px;
            }

            .course-title {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🎓 LearnSphere</h1>
            <div class="subtitle">Hệ thống quản lý học tập trực tuyến</div>
        </div>

        <!-- Main Content -->
        <div class="content">
            <div class="greeting">
                Xin chào giảng viên! 👋
            </div>

            <p style="margin-bottom: 20px; color: #555;">
                Chúng tôi vui mừng thông báo rằng có một học viên mới vừa đăng ký tham gia khóa học của bạn.
            </p>

            <!-- Course Information -->
            <div class="course-info">
                <div class="course-title">
                    📚 {{ $course->title }}
                </div>
                <div class="course-subtitle">
                    Khóa học vừa nhận được đăng ký mới
                </div>
            </div>

            <div class="divider"></div>

            <!-- Order Details -->
            <div class="order-details">
                <h3>📋 Thông tin đơn hàng</h3>

                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Mã đơn hàng</div>
                        <div class="info-value">
                            <span class="highlight-number">#{{ $order->id }}</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Học viên</div>
                        <div class="info-value">
                            👤 User ID: {{ $order->user_id }}
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Ngày đăng ký</div>
                        <div class="info-value">
                            🗓️ {{ $order->created_at->format('d/m/Y') }}
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Thời gian</div>
                        <div class="info-value">
                            ⏰ {{ $order->created_at->format('H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Call to Action -->
            <div class="action-section">
                <p style="margin-bottom: 15px; color: #555;">
                    Bạn có thể đăng nhập vào hệ thống để View Detail và bắt đầu tương tác với học viên mới.
                </p>
                <a href="{{ url('/admin/dashboard') }}" class="cta-button">
                    🚀 Đăng nhập hệ thống
                </a>
            </div>

            <p style="margin-top: 25px; color: #666; font-size: 14px;">
                💡 <strong>Lưu ý:</strong> Hãy chuẩn bị nội dung học tập chất lượng và tương tác tích cực với học viên để tạo ra trải nghiệm học tập tốt nhất.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-logo">LearnSphere</div>
            <div style="opacity: 0.8;">
                Nền tảng học tập trực tuyến hàng đầu Việt Nam<br>
                © {{ date('Y') }} LearnSphere. Tất cả quyền được bảo lưu.
            </div>
        </div>
    </div>
</body>
</html>
