<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=display-width, initial-scale=1.0, maximum-scale=1.0," />
    <title>Welcome to {{ config('app.name') }}</title>
</head>

<body style="font-family: 'Arial', sans-serif; background-color: #f9f9f9; margin: 0; padding: 0;">
    <div class="container"
        style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); padding: 20px;">

        <div class="header" style="background-color: #007bff; color: white; text-align: center; padding: 20px 0;">
            <h1 style="margin: 0; font-size: 24px;">Welcome to {{ config('app.name') }}!</h1>
        </div>

        <div class="content" style="padding: 20px;">
            <h2 style="margin: 15px 0; text-align: center;">Hello {{ $user->name ?? 'User' }}!</h2>
            <p>We’re thrilled to have you on board. 🎉</p>
            <p>Feel free to explore and make the most out of our platform.</p>
            <p>If you need any help, our support team is always here for you.</p>
            <p>Best regards,</p>
            <p><strong>{{ config('app.name') }} Team</strong></p>
        </div>

        <div class="footer" style="text-align: center; padding: 20px; background-color: #f1f1f1;">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
