<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Welcome to Your New Email</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f7;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333333;
        }

        p {
            color: #555555;
            line-height: 1.6;
            font-size: 16px;
        }

        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #888888;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <h2>Hello {{ $user->name }},</h2>

        <p>
            This email address has been successfully registered as your new contact email in our system.
        </p>

        <p>
            <strong>Your new email:</strong> {{ $user->email }}<br>
            <strong>Your new password:</strong> {{ $password }}
        </p>

        <p>
            We're glad to have you with us and appreciate your continued trust! 😊
        </p>

        <div class="footer">
            Best regards,<br>
            The {{ config('app.name') }} Team
        </div>
    </div>
</body>

</html>
