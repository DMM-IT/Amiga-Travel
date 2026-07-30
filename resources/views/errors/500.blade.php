<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Something went wrong</title>
    <style>
        body { margin:0; font-family: Arial, sans-serif; background:#f8fafc; color:#0f172a; display:grid; place-items:center; min-height:100vh; }
        .card { max-width:620px; width:calc(100% - 2rem); background:white; padding:2rem; border-radius:16px; box-shadow:0 20px 45px rgba(15,23,42,0.08); }
        .status { font-size:3rem; font-weight:700; color:#b45309; }
        h1 { margin:0.5rem 0 0.75rem; font-size:1.5rem; }
        p { line-height:1.6; }
        a { color:#2563eb; font-weight:600; text-decoration:none; }
    </style>
</head>
<body>
    <div class="card">
        <div class="status">500</div>
        <h1>Something went wrong</h1>
        <p>We’re sorry, but the website hit an unexpected problem. Our team has been notified, and you can try again shortly.</p>
        <p><a href="{{ url('/') }}">Return home</a></p>
    </div>
</body>
</html>
