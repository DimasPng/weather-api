<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Unsubscribe</title>
</head>
<body>
    <p>Hello,</p>
    <p>You are currently subscribed to weather updates for <strong>{{ $city }}</strong>.</p>
    <p>If you no longer wish to receive updates, click the link below to unsubscribe:</p>
    <p><a href="{{ $unsubscribeUrl }}">{{ $unsubscribeUrl }}</a></p>
    <p>If you did not request this, you can ignore this email.</p>
</body>
</html>
