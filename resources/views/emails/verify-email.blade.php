@component('mail::message')
# Verify Your Email Address

Hello {{ $user->name }},

Welcome to **EverPeak Camp**! Thank you for registering with us. 

To complete your registration and verify your email address, please click the button below:

@component('mail::button', ['url' => $verificationUrl])
Verify Email Address
@endcomponent

Or copy and paste this link in your browser:
{{ $verificationUrl }}

**This verification link will expire in 24 hours.**

If you did not create this account, please ignore this email.

Thanks,<br>
{{ config('app.name') }}

---

**Why verify your email?**
- Keeps your account secure
- Ensures we can contact you about important updates
- Protects against unauthorized account creation
@endcomponent
