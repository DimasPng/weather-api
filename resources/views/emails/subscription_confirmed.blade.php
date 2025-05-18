<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscription Confirmed</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            padding: 40px;
        }
        h1 {
            font-size: 22px;
            color: #2e7d32;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            margin-top: 16px;
            font-size: 16px;
            color: #fff;
            background-color: #d32f2f;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>✅ Subscription Confirmed</h1>

    <p>Hello <strong>{{ $subscription->email }}</strong>,</p>

    <p>Your subscription to weather updates for <strong>{{ $subscription->city }}</strong> has been successfully confirmed.</p>

    <p>You will now receive updates <strong>{{ $subscription->frequency }}</strong>.</p>

    <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">

    <p>If you wish to unsubscribe, click the button below:</p>

    <a href="{{ url("/unsubscribe/{$subscription->unsubscribe_token}") }}" class="button">Unsubscribe</a>
</div>
</body>
</html>
