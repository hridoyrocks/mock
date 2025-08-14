<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Change Verification</title>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #C8102E 0%, #A00E27 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .email-body {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #1f2937;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            color: #4b5563;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .otp-container {
            background: linear-gradient(135deg, #C8102E 0%, #A00E27 100%);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-label {
            color: #ffffff;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }
        .otp-code {
            color: #ffffff;
            font-size: 36px;
            font-weight: 700;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
        }
        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px 20px;
            margin: 30px 0;
            border-radius: 6px;
        }
        .warning-text {
            color: #92400e;
            font-size: 14px;
            margin: 0;
        }
        .footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer-text {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
        }
        .footer-link {
            color: #C8102E;
            text-decoration: none;
            font-weight: 600;
        }
        .footer-link:hover {
            text-decoration: underline;
        }
        .expires-text {
            color: #ffffff;
            font-size: 12px;
            margin-top: 15px;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>🎓 IELTS Mock Platform</h1>
        </div>
        
        <div class="email-body">
            <div class="greeting">Hello {{ $userName }},</div>
            
            <div class="message">
                We received a request to change your email address on your IELTS Mock Platform account. 
                To confirm this change, please use the verification code below:
            </div>
            
            <div class="otp-container">
                <div class="otp-label">Your Verification Code</div>
                <div class="otp-code">{{ $otp }}</div>
                <div class="expires-text">This code expires in 10 minutes</div>
            </div>
            
            <div class="warning">
                <p class="warning-text">
                    <strong>Important:</strong> If you didn't request this email change, please ignore this message. 
                    Your email address will remain unchanged.
                </p>
            </div>
            
            <div class="message">
                For your security, this verification code will expire in 10 minutes. 
                If you need a new code, you can request one from your profile settings.
            </div>
        </div>
        
        <div class="footer">
            <p class="footer-text">
                © {{ date('Y') }} IELTS Mock Platform. All rights reserved.<br>
                Need help? <a href="{{ url('/') }}" class="footer-link">Visit our support center</a>
            </p>
        </div>
    </div>
</body>
</html>
