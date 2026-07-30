<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Error' }}</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Arial, sans-serif;
        }
        body {
            margin: 0;
            background: #f8fafc;
            color: #0f172a;
            display: grid;
            place-items: center;
            min-height: 100vh;
        }
        .card {
            max-width: 620px;
            width: calc(100% - 2rem);
            padding: 2rem;
            border-radius: 16px;
            background: white;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
        }
        .status {
            font-size: 3rem;
            font-weight: 700;
            margin: 0 0 0.5rem;
            color: #b45309;
        }
        h1 {
            margin: 0 0 0.75rem;
            font-size: 1.5rem;
        }
        p {
            margin: 0 0 1rem;
            line-height: 1.6;
        }
        a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="status">{{ $status ?? 500 }}</div>
        <h1>{{ $title ?? 'Something went wrong' }}</h1>
        <p>{{ $message ?? 'We could not complete your request right now. Please try again shortly.' }}</p>
        <p><a href="{{ url('/') }}">Return home</a></p>
    </div>
</body>
</html>
