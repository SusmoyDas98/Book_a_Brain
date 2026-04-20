<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bKash — Payment Cancelled</title>
    @php
        $dashboardUrl = route('dashboard');
    @endphp
    <meta http-equiv="refresh" content="3;url={{ $dashboardUrl }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(160deg, #c2185b 0%, #E2136E 50%, #ad1457 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .bkash-wrapper { width: 100%; max-width: 400px; }
        .bkash-logo { text-align: center; margin-bottom: 1.5rem; }
        .bkash-card { background: white; border-radius: 20px; padding: 2.5rem 2rem; box-shadow: 0 20px 60px rgba(0,0,0,0.25); text-align: center; }
        .icon-circle {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            box-shadow: 0 8px 24px rgba(148,163,184,0.4);
            position: relative;
        }
        .icon-circle::before,
        .icon-circle::after {
            content: '';
            position: absolute;
            width: 36px;
            height: 5px;
            background: white;
            border-radius: 3px;
        }
        .icon-circle::before { transform: rotate(45deg); }
        .icon-circle::after  { transform: rotate(-45deg); }
        .card-heading { font-size: 1.3rem; font-weight: 800; color: #1a1a1a; margin-bottom: 0.5rem; }
        .card-subtext { font-size: 0.88rem; color: #555; margin-bottom: 0.75rem; }
        .redirect-text { font-size: 0.78rem; color: #aaa; margin-bottom: 1.5rem; }
        .btn-dashboard {
            display: inline-block;
            background: #E2136E;
            color: white;
            border-radius: 10px;
            padding: 0.7rem 1.5rem;
            font-size: 0.9rem;
            font-weight: 700;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-dashboard:hover { background: #c2185b; }
    </style>
</head>
<body>
    <div class="bkash-wrapper">
        <div style="text-align: center; margin-bottom: 6px;">
            <img src="{{ asset('images/bkash-logo.png') }}"
                 alt="bKash"
                 style="height: 52px; width: auto; filter: brightness(0) invert(1);">
        </div>
        <p style="font-size: 11px; color: rgba(255,255,255,0.75); text-align: center; margin: 0 0 4px;">
            Powered by Book-a-Brain Portal
        </p>

        <div class="bkash-card">
            <div class="icon-circle"></div>
            <h1 class="card-heading">Payment Cancelled</h1>
            <p class="card-subtext">You cancelled the payment. No charges were made.</p>
            <p class="redirect-text">You will be redirected to your dashboard in 3 seconds...</p>
            <a href="{{ $dashboardUrl }}" class="btn-dashboard">Go to Dashboard</a>
        </div>
    </div>
</body>
</html>
