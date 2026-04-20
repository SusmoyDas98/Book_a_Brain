<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bKash — Enter Account Number</title>
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
        .bkash-wrapper {
            width: 100%;
            max-width: 400px;
        }
        .bkash-logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .bkash-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        }
        .step-indicator {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-bottom: 1.5rem;
        }
        .step-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #e0e0e0;
        }
        .step-dot.active {
            background: #E2136E;
            width: 24px;
            border-radius: 4px;
        }
        .card-heading {
            font-size: 1.1rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 0.4rem;
            text-align: center;
        }
        .card-subtext {
            font-size: 0.82rem;
            color: #888;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            color: #555;
            margin-bottom: 0.4rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            color: #1a1a1a;
            outline: none;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            border-color: #E2136E;
        }
        .error-text {
            color: #e53935;
            font-size: 0.78rem;
            margin-top: 0.3rem;
        }
        .btn-primary {
            width: 100%;
            background: #E2136E;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.85rem;
            font-size: 1rem;
            font-weight: 800;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 0.5rem;
        }
        .btn-primary:hover {
            background: #c2185b;
        }
        .cancel-form {
            text-align: center;
            margin-top: 1rem;
        }
        .cancel-btn {
            background: none;
            border: none;
            color: #aaa;
            font-size: 0.85rem;
            cursor: pointer;
            text-decoration: underline;
            padding: 0;
        }
        .cancel-btn:hover {
            color: #555;
        }
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
                <div class="step-dot active"></div>
                <div class="step-dot"></div>
                <div class="step-dot"></div>
            </div>

            <h1 class="card-heading">Enter your bKash Account Number</h1>
            <p class="card-subtext">Please enter your registered bKash mobile number</p>

            <form method="POST" action="{{ route('bkash.portal.phone.submit') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="phone_number">Mobile Number</label>
                    <input
                        type="tel"
                        id="phone_number"
                        name="phone_number"
                        class="form-control"
                        placeholder="01XXXXXXXXX"
                        value="{{ old('phone_number') }}"
                        autofocus
                    >
                    @error('phone_number')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-primary">Next →</button>
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
