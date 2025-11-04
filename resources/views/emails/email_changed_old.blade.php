<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Email Change Notification</title>
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

        .highlight {
            font-weight: bold;
            color: #000;
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
        <h2>Hello,</h2>
        <p>
            We wanted to let you know that your email address has been successfully changed.
        </p>
        <p>
            <span class="highlight">Previous email:</span> {{ $oldEmail }}<br>
            <span class="highlight">New email:</span> {{ $newEmail }}
        </p>
        <p>
            If you did not request this change, please contact our support team immediately.
        </p>
        <div class="footer">
            Thank you,<br>
            The {{ config('app.name') }} Team
        </div>
    </div>
</body>

</html>
