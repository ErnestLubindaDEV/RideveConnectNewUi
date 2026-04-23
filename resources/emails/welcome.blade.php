<!DOCTYPE html>
<html>
<head>
    <title>Welcome to RideveConnect</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background-color: #1daeec;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .footer {
            background-color: #f1f1f1;
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #666;
        }
        .button {
            display: inline-block;
            background-color: #1daeec;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to RideveConnect</h1>
        </div>
        <div class="content">
            <p>Dear {{ $user->name }},</p>
            <p>We are thrilled to welcome you to RideveConnect, our innovative ERP platform designed to simplify and enhance your workflow.</p>
            <p><strong>Your Login Credentials:</strong></p>
            <ul>
                <li><strong>Email:</strong> {{ $user->email }}</li>
                <li><strong>Password:</strong> {{ $password }}</li>
            </ul>
            <p><strong>Why RideveConnect?</strong></p>
            <p>RideveConnect streamlines business processes, improves collaboration, and provides real-time insights to help you achieve your goals efficiently.</p>
            <p>Click the button below to log in and start exploring:</p>
            <p style="text-align: center;">
                <a href="https://rideveconnect.com" class="button">Log In</a>
            </p>
            <p>We recommend updating your password as soon as possible to ensure the security of your account.</p>
            <p>If you have any questions, feel free to reach out to our support team at <a href="mailto:admin@ridevemedia.com">it@ridevemedia.com</a>.</p>
            <p>Thank you for being part of RideveConnect. We’re excited to see what we can achieve together!</p>
            <p>Best regards,</p>
            <p>Rideve Media Development</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Rideve Media. All rights reserved.
        </div>
    </div>
</body>
</html>
