<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f5f7f6; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(26, 71, 42, 0.08); }
        .header { background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%); color: white; padding: 40px 30px; text-align: center; }
        .header-title { font-size: 28px; font-weight: 700; margin-bottom: 5px; }
        .header-subtitle { font-size: 14px; opacity: 0.9; }
        .content { padding: 40px 30px; }
        .Welcome-text { font-size: 18px; font-weight: 700; color: #1a472a; margin-bottom: 15px; }
        .content p { margin: 15px 0; color: #555; }
        .button { display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%); color: white; text-decoration: none; border-radius: 6px; font-weight: 600; margin: 20px 0; font-size: 14px; }
        .button:hover { opacity: 0.95; }
        .code-box { background-color: #f9fef8; padding: 15px; border-radius: 6px; border-left: 4px solid #acd168; margin: 15px 0; word-break: break-all; font-size: 12px; color: #1a472a; font-family: monospace; }
        .info-section { background-color: #f9fef8; padding: 20px; border-radius: 6px; margin: 20px 0; }
        .info-title { font-weight: 700; color: #1a472a; margin-bottom: 10px; }
        .info-section ul { margin-left: 20px; color: #666; font-size: 14px; }
        .info-section li { margin: 8px 0; }
        .warning { background-color: #fff3cd; padding: 12px; border-radius: 4px; color: #856404; font-size: 13px; margin: 15px 0; border-left: 4px solid #ffc107; }
        .footer { background-color: #f9fef8; border-top: 1px solid #e8e8e8; padding: 25px 30px; text-align: center; font-size: 12px; color: #666; }
        .footer-text { margin: 8px 0; }
        .divider { height: 1px; background: linear-gradient(to right, transparent, #e0e0e0, transparent); margin: 25px 0; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-title">{{ config('app.name') }}</div>
            <div class="header-subtitle">Email Verification</div>
        </div>
        <div class="content">
            <div class="welcome-text">✉️ Verify Your Email Address</div>
            
            <p>Hello {{ $user->first_name }} {{ $user->last_name }},</p>
            
            <p>Welcome to <strong>{{ config('app.name') }}</strong>! Thank you for registering and trusting your information with us.</p>
            
            <p>To complete your registration and verify your email address, please click the button below:</p>
            
            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="button">Verify Email Address</a>
            </div>
            
            <p style="font-size: 13px; color: #999;">Or copy and paste this link in your browser:</p>
            <div class="code-box">{{ $verificationUrl }}</div>
            
            <div class="warning">
                ⏰ <strong>This verification link will expire in 24 hours.</strong>
            </div>

            <div class="divider"></div>

            <div class="info-section">
                <div class="info-title">🔒 Why verify your email?</div>
                <ul>
                    <li>Keeps your account secure and protected</li>
                    <li>Ensures we can contact you about important updates and order info</li>
                    <li>Protects against unauthorized account creation</li>
                    <li>Allows you to receive tracking and delivery notifications</li>
                </ul>
            </div>

            <p style="color: #999; font-size: 13px; margin-top: 20px;"><strong>Didn't create this account?</strong> If you didn't sign up for {{ config('app.name') }}, please ignore this email. Your account won't be created until you verify your email.</p>

            <p style="margin-top: 25px;">Thanks,<br>
            <strong>{{ config('app.name') }} Team</strong></p>
        </div>
        <div class="footer">
            <div class="footer-text">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</div>
            <div class="footer-text" style="margin-top: 15px; font-size: 11px; color: #999;">This is an automated message, please do not reply to this address directly.</div>
        </div>
    </div>
</body>
</html>
