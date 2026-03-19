<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book-a-Brain</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/landing_page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <style>
        :root {
            --brand-primary: #6366f1;
            --brand-dark:    #0f172a;
            --brand-accent:  #4f46e5;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(circle at 15% 10%, rgba(99,102,241,.10), transparent 35%),
                radial-gradient(circle at 85% 20%, rgba(99,102,241,.07), transparent 35%),
                linear-gradient(rgba(99,102,241,0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99,102,241,0.06) 1px, transparent 1px),
                linear-gradient(#eef2ff, #dbeafe);
            background-size: auto, auto, 40px 40px, 40px 40px, auto;
            min-height: 100vh;
            color: var(--brand-dark);
            margin: 0;
        }

        select option { background: #f8fafc; color: #0f172a; }

        .bab-input {
            width: 100%;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            padding: 0.75rem 1rem;
            color: #0f172a;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .bab-input:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 4px rgba(99,102,241,0.12);
        }

        .bab-card {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(18px);
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            padding: 1.75rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 8px 30px rgba(99,102,241,0.07);
        }

        .bab-label {
            color: #64748b;
            font-size: 0.78rem;
            display: block;
            margin-bottom: 0.4rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .bab-section-title {
            color: #0f172a;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 1.25rem;
        }

        .bab-meta-label {
            font-size: 0.72rem;
            color: #94a3b8;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .bab-meta-value { color: #1e293b; margin-bottom: 0; font-weight: 500; }

        .bab-btn-primary {
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent));
            color: white;
            font-weight: 700;
            border: none;
            border-radius: 14px;
            padding: 0.8rem 2rem;
            cursor: pointer;
            font-size: 0.95rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            text-decoration: none;
            display: inline-block;
            transition: opacity 0.2s, box-shadow 0.2s;
            box-shadow: 0 6px 20px rgba(99,102,241,0.3);
        }
        .bab-btn-primary:hover {
            opacity: 0.9;
            color: white;
            box-shadow: 0 8px 25px rgba(99,102,241,0.4);
        }

        .bab-btn-secondary {
            background: white;
            color: #64748b;
            font-weight: 600;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            padding: 0.8rem 2rem;
            text-decoration: none;
            font-size: 0.95rem;
            display: inline-block;
            transition: border-color 0.2s, color 0.2s;
        }
        .bab-btn-secondary:hover {
            border-color: var(--brand-primary);
            color: var(--brand-primary);
        }

        .bab-file-input {
            width: 100%;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            padding: 0.55rem 1rem;
            color: #64748b;
            font-size: 0.85rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .bab-file-input::file-selector-button {
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent));
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.3rem 0.9rem;
            font-weight: 700;
            cursor: pointer;
            margin-right: 0.75rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .bab-file-input::file-selector-button:hover { opacity: 0.9; }
    </style>
</head>
<body>
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    

</body>
</html>