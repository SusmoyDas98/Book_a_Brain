<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bKash — Enter PIN</title>
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
        .bkash-card { background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 20px 60px rgba(0,0,0,0.25); }
        .step-indicator { display: flex; justify-content: center; gap: 6px; margin-bottom: 1.5rem; }
        .step-dot { width: 8px; height: 8px; border-radius: 50%; background: #e0e0e0; }
        .step-dot.done { background: #E2136E; }
        .step-dot.active { background: #E2136E; width: 24px; border-radius: 4px; }
        .card-heading { font-size: 1.1rem; font-weight: 800; color: #1a1a1a; margin-bottom: 0.4rem; text-align: center; }
        .card-subtext { font-size: 0.82rem; color: #888; text-align: center; margin-bottom: 1rem; }
        .amount-box {
            background: #fff5f8;
            border: 2px solid #fce4ec;
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            margin-bottom: 1.25rem;
        }
        .amount-label { font-size: 0.72rem; color: #aaa; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem; }
        .amount-value { font-size: 1.5rem; font-weight: 900; color: #E2136E; }
        .form-group { margin-bottom: 1rem; }
        .form-label { display: block; font-size: 0.78rem; font-weight: 700; color: #555; margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.4px; }
        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1.4rem;
            color: #1a1a1a;
            outline: none;
            transition: border-color 0.2s;
            text-align: center;
            letter-spacing: 0.5rem;
        }
        .form-control:focus { border-color: #E2136E; }
        .error-text { color: #e53935; font-size: 0.78rem; margin-top: 0.3rem; text-align: center; }
        .btn-primary { width: 100%; background: #E2136E; color: white; border: none; border-radius: 10px; padding: 0.85rem; font-size: 1rem; font-weight: 800; cursor: pointer; transition: background 0.2s; margin-top: 0.5rem; }
        .btn-primary:hover { background: #c2185b; }
        .cancel-form { text-align: center; margin-top: 1rem; }
        .cancel-btn { background: none; border: none; color: #aaa; font-size: 0.85rem; cursor: pointer; text-decoration: underline; padding: 0; }
        .cancel-btn:hover { color: #555; }
        .hint-text { font-size: 0.75rem; color: #bbb; text-align: center; margin-top: 0.5rem; }
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
            <div class="step-indicator">
                <div class="step-dot done"></div>
                <div class="step-dot done"></div>
                <div class="step-dot active"></div>
            </div>

            <h1 class="card-heading">Enter bKash PIN</h1>
            <p class="card-subtext">Please enter your bKash account PIN to confirm payment</p>

            <div class="amount-box">
                <p class="amount-label">Payment Amount</p>
                <p class="amount-value">BDT {{ number_format(session('bkash_payment.amount', 0), 2) }}</p>
            </div>

            <form method="POST" action="{{ route('bkash.portal.password.submit') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="password">bKash PIN</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="----"
                        maxlength="4"
                        autofocus
                        autocomplete="current-password"
                    >
                    @error('password')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                    <p class="hint-text">Demo PIN: 1111</p>
                </div>

                <button type="submit" class="btn-primary">Confirm Payment</button>
            </form>

            <div class="cancel-form">
                <form method="POST" action="{{ route('bkash.portal.cancel') }}">
                    @csrf
                    <button type="submit" class="cancel-btn">Cancel Payment</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
