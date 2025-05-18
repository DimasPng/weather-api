<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Weather Update for {{ $city }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .weather-container {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .weather-header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .weather-detail {
            margin: 5px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <p>Hello,</p>
    <p>Here is your weather update for <strong>{{ $city }}</strong>:</p>

    <div class="weather-container">
        <div class="weather-header">Current Weather Conditions</div>
        @if(isset($weatherData['temperature']))
            <div class="weather-detail">Temperature: {{ $weatherData['temperature'] }}°C</div>
        @endif

        @if(isset($weatherData['humidity']))
            <div class="weather-detail">Humidity: {{ $weatherData['humidity'] }}%</div>
        @endif

        @if(isset($weatherData['description']))
            <div class="weather-detail">Description: {{ $weatherData['description'] }} km/h</div>
        @endif
    </div>

    <p>Stay prepared and have a great day!</p>

    <div class="footer">
        <p>You are receiving this email because you subscribed to weather updates for {{ $city }}.</p>
        <p>If you no longer wish to receive these updates, <a href="{{ $unsubscribeUrl }}">click here to unsubscribe</a>.</p>
    </div>
</body>
</html>
