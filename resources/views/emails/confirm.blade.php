<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Your Subscription</title>
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
            margin-bottom: 20px;
            color: #222;
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
            background-color: #1976d2;
            text-decoration: none;
            border-radius: 4px;
        }
        .footer {
            font-size: 13px;
            color: #888;
            margin-top: 30px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Confirm Your Subscription</h1>
    <p>Hello,</p>
    <p>Thank you for subscribing to weather updates for <strong>{{ $sub->city }}</strong>.</p>
    <p>Please confirm your subscription by clicking the button below:</p>

    <a href="{{ $confirmUrl }}" class="button">Confirm Subscription</a>

    <p class="footer">If you didn’t request this, feel free to ignore this email.</p>
</div>
</body>
</html>
