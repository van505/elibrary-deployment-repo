<!DOCTYPE html>
<html>
<head>
    <title>Password Changed</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Your password was changed</h2>
    <p>Hi {{ $user->first_name ?? 'User' }},</p>
    <p>This is a security notification to inform you that your account password was recently changed.</p>
    
    <ul>
        <li><strong>Time:</strong> {{ $time }}</li>
        <li><strong>IP Address:</strong> {{ $ip }}</li>
    </ul>

    <p style="color: #d9534f; font-weight: bold;">If this was not you, please contact the system administrator immediately.</p>
    
    <p>Thanks,<br>{{ config('app.name') }}</p>
</body>
</html>
