<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirm Your Subscription</title>
</head>
<body>
    <p>Hello,</p>
    <p>Thank you for subscribing to the weather updates for {{ $sub->city }}.</p>
    <p>Please confirm your subscription by clicking the link below:</p>
    <p><a href="{{ $confirmUrl }}">Confirm Subscription</a></p>
    <p>If you didn't request this, you can ignore this email.</p>
</body>
</html>
