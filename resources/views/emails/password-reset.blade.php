<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP - HRM System</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333333;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table {
            border-collapse: collapse;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #0177bc 0%, #004b79 100%);
            padding: 35px 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 6px 0 0 0;
            font-size: 13px;
            opacity: 0.9;
        }
        .content {
            padding: 35px 30px;
            text-align: center;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 12px;
        }
        .message {
            font-size: 14px;
            line-height: 1.6;
            color: #64748b;
            margin-bottom: 28px;
        }
        .otp-box {
            background-color: #f8fafc;
            border: 2px dashed #0177bc;
            border-radius: 10px;
            padding: 22px 20px;
            margin: 0 auto 28px auto;
            max-width: 320px;
        }
        .otp-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .otp-code {
            font-size: 36px;
            font-weight: 800;
            color: #0177bc;
            letter-spacing: 8px;
            font-family: 'Courier New', Courier, monospace;
        }
        .expiry-text {
            font-size: 13px;
            color: #ef4444;
            font-weight: 500;
            margin-top: 8px;
        }
        .warning-box {
            background-color: #fffbe6;
            border-left: 4px solid #faad14;
            padding: 14px 16px;
            border-radius: 4px;
            text-align: left;
            font-size: 13px;
            color: #722ed1;
            margin-bottom: 30px;
            line-height: 1.5;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #f1f5f9;
            font-size: 12px;
            color: #94a3b8;
        }
        .footer p {
            margin: 4px 0;
        }
    </style>
</head>
<body>

<div class="email-container">
    <!-- Header -->
    <div class="header">
        <h1>HRM System</h1>
        <p>Security & Verification Services</p>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="greeting">Password Reset Request</div>
        <div class="message">
            We received a request to reset your password for your Banking HRM System account. Use the verification OTP below to complete your password reset:
        </div>

        <!-- OTP Box -->
        <div class="otp-box">
            <div class="otp-label">Your One-Time Password</div>
            <div class="otp-code">{{ $otp }}</div>
            <div class="expiry-text">⏱️ Valid for 10 minutes</div>
        </div>

        <!-- Security Warning -->
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td style="background-color: #fff7ed; border-left: 4px solid #f97316; padding: 14px 16px; border-radius: 4px; text-align: left; font-size: 13px; color: #9a3412; line-height: 1.5;">
                    <strong>🔒 Security Note:</strong> If you did not request a password reset, please ignore this email or contact your IT Support Administrator immediately. Never share your OTP with anyone.
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>© {{ date('Y') }} Banking HRM System. All rights reserved.</p>
        <p>This is an automated system notification. Please do not reply to this email.</p>
    </div>
</div>

</body>
</html>
