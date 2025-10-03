@php
    $appName = config('app.name', 'LearnSphere');
@endphp

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác minh email - {{ $appName }}</title>
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

        .main-message {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }

        .verify-section {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            text-align: center;
            margin: 25px 0;
        }

        .verify-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .verify-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .expiration-notice {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .expiration-notice .icon {
            color: #856404;
            margin-right: 8px;
        }

        .security-tips {
            background-color: #e8f4fd;
            border: 1px solid #b8daff;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }

        .security-tips h4 {
            color: #004085;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .security-tips ul {
            margin: 0;
            padding-left: 20px;
            color: #004085;
            font-size: 13px;
        }

        .security-tips li {
            margin-bottom: 5px;
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

        .countdown-timer {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 13px;
            font-weight: 600;
        }

        /* Responsive design */
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0;
                box-shadow: none;
            }

            .header {
                padding: 20px 15px;
            }

            .content {
                padding: 20px 15px;
            }

            .main-message {
                padding: 15px;
            }

            .verify-section {
                padding: 20px 15px;
            }

            .verify-button {
                padding: 12px 30px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🎓 {{ $appName }}</h1>
            <div class="subtitle">Nền tảng học tập trực tuyến</div>
        </div>

        <!-- Main Content -->
        <div class="content">
            <div class="greeting">
                Xin chào! 👋
            </div>

            <p style="margin-bottom: 20px; color: #555;">
                Cảm ơn bạn đã tham gia cộng đồng <strong>{{ $appName }}</strong>!
                Chúng tôi rất vui mừng chào đón bạn.
            </p>

            <!-- Main Message -->
            <div class="main-message">
                <div style="font-size: 16px; font-weight: 600; color: #2c3e50; margin-bottom: 10px;">
                    🔐 Xác minh địa chỉ email của bạn
                </div>
                <div style="color: #7f8c8d; font-size: 14px;">
                    Để đảm bảo tính bảo mật và hoàn tất quá trình đăng ký, vui lòng xác minh email của bạn.
                </div>
            </div>

            <!-- Verification Section -->
            <div class="verify-section">
                <div style="margin-bottom: 20px;">
                    <h3 style="color: #2c3e50; margin-bottom: 10px;">Nhấn vào nút bên dưới để xác minh</h3>
                    <p style="color: #666; font-size: 14px;">
                        Chỉ mất vài giây để hoàn tất quá trình này
                    </p>
                </div>

                <a href="{{ $actionUrl }}" class="verify-button">
                    ✅ Xác minh Email
                </a>
            </div>

            <!-- Expiration Notice -->
            <div class="expiration-notice">
                <span class="icon">⏰</span>
                <strong>Thời gian hiệu lực:</strong> Link xác minh này sẽ hết hạn trong
                <span class="countdown-timer">{{ $expiration }} phút</span>
            </div>

            <div class="divider"></div>

            <!-- Security Tips -->
            <div class="security-tips">
                <h4>🛡️ Lưu ý bảo mật:</h4>
                <ul>
                    <li>Email này được gửi từ hệ thống {{ $appName }} chính thức</li>
                    <li>Không chia sẻ link xác minh với người khác</li>
                    <li>Nếu bạn không đăng ký tài khoản, vui lòng bỏ qua email này</li>
                    <li>Link chỉ có thể sử dụng một lần duy nhất</li>
                </ul>
            </div>

            <p style="margin-top: 25px; color: #666; font-size: 14px; text-align: center;">
                🎯 <strong>Sau khi xác minh:</strong> Bạn sẽ có thể truy cập đầy đủ các tính năng của {{ $appName }}
                và bắt đầu hành trình học tập tuyệt vời!
            </p>

            <div style="margin-top: 30px; padding: 15px; background-color: #f8f9fa; border-radius: 8px; text-align: center;">
                <p style="color: #666; font-size: 13px; margin: 0;">
                    Nếu nút xác minh không hoạt động, bạn có thể copy link bên dưới vào trình duyệt:
                </p>
                <p style="word-break: break-all; color: #667eea; font-size: 12px; margin: 10px 0 0 0;">
                    {{ $actionUrl }}
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-logo">{{ $appName }}</div>
            <div style="opacity: 0.8;">
                Nền tảng học tập trực tuyến hàng đầu Việt Nam<br>
                © {{ date('Y') }} {{ $appName }}. Tất cả quyền được bảo lưu.
            </div>
        </div>
    </div>
</body>
</html>
