<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Login Verification Code</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f9fafb; margin:0; padding:20px;">
    <div style="max-width:480px; margin:0 auto; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <!-- Header -->
        <div style="background:#1e40af; padding:28px 32px;">
            <h1 style="color:#fff; margin:0; font-size:20px; font-weight:700;">📚 ELibrary</h1>
            <p style="color:#bfdbfe; margin:6px 0 0; font-size:14px;">Login Verification</p>
        </div>

        <!-- Body -->
        <div style="padding:32px;">
            <p style="color:#374151; font-size:15px; margin-top:0;">Hi <strong>{{ $user->first_name ?? $user->email }}</strong>,</p>
            <p style="color:#374151; font-size:15px;">Use the code below to complete your login. This code is valid for <strong>10 minutes</strong>.</p>

            <!-- OTP Box -->
            <div style="background:#f1f5f9; border:2px dashed #3b82f6; border-radius:10px; text-align:center; padding:24px; margin:24px 0;">
                <p style="color:#6b7280; font-size:13px; margin:0 0 8px;">Your verification code</p>
                <p style="color:#1e40af; font-size:40px; font-weight:800; letter-spacing:12px; margin:0; font-family:monospace;">{{ $otp }}</p>
            </div>

            <p style="color:#6b7280; font-size:13px;">If you did not attempt to log in, please ignore this email and consider changing your password immediately.</p>
            <p style="color:#6b7280; font-size:13px; margin-bottom:0;">
                <strong>Time:</strong> {{ now()->format('Y-m-d H:i:s') }} UTC<br>
                <strong>IP:</strong> {{ request()->ip() }}
            </p>
        </div>

        <!-- Footer -->
        <div style="background:#f9fafb; border-top:1px solid #e5e7eb; padding:16px 32px; text-align:center;">
            <p style="color:#9ca3af; font-size:12px; margin:0;">{{ config('app.name') }} — Do not share this code with anyone.</p>
        </div>
    </div>
</body>
</html>
