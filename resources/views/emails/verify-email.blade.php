<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%); color: white; padding: 30px; text-align: center; }
        .content { padding: 30px; }
        .content h2 { color: #1a472a; margin-top: 0; }
        .content p { margin: 15px 0; }
        .button { display: inline-block; padding: 12px 30px; background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%); color: white; text-decoration: none; border-radius: 5px; font-weight: 600; margin: 20px 0; }
        .footer { background-color: #f9f9f9; border-top: 1px solid #e0e0e0; padding: 20px; text-align: center; font-size: 12px; color: #666; }
        .divider { border-top: 1px solid #e0e0e0; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
        </div>
        <div class="content">
            <h2>Verify Your Email Address</h2>
            
            <p>Hello {{ $user->name }},</p>
            
            <p>Welcome to <strong>EverPeak Camp</strong>! Thank you for registering with us.</p>
            
            <p>To complete your registration and verify your email address, please click the button below:</p>
            
            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="button">Verify Email Address</a>
            </div>
            
            <p>Or copy and paste this link in your browser:<br>
            <code>{{ $verificationUrl }}</code></p>
            
            <p><strong>This verification link will expire in 24 hours.</strong></p>
            
            <p>If you did not create this account, please ignore this email.</p>
            
            <p>Thanks,<br>
            {{ config('app.name') }}</p>
            
            <div class="divider"></div>
            
            <h3 style="font-size: 14px; color: #666;">Why verify your email?</h3>
            <ul style="font-size: 14px; color: #666;">
                <li>Keeps your account secure</li>
                <li>Ensures we can contact you about important updates</li>
                <li>Protects against unauthorized account creation</li>
            </ul>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
