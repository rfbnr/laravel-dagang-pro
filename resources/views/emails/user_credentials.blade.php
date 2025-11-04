<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Account Notification</title>
</head>

<body style="font-family: Arial, sans-serif;">
    @if ($type == 'old')
        <h2>Hello {{ $user->name }},</h2>
        <p>Your email has been changed. If this wasn't you, please contact support.</p>
    @else
        <h2>Hello {{ $user->name }},</h2>
        <p>This is your new email address registered in our system.</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Password:</strong> {{ $password }}</p>
        <p>Please log in and change your password as soon as possible.</p>
    @endif

    <br><br>
    <p>Best regards,<br>
        The {{ config('app.name') }} Team</p>
</body>

</html>
