@extends('layouts.app')

@section('content')

<div style="min-height:100vh; padding: 2.5rem 0 5rem; margin-top:40px;">
<div class="container" style="max-width:600px;">

    {{-- HEADER --}}
    <div class="mb-4">
        <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.3em;color:#E2136E;font-weight:700;">Book-a-Brain × bKash</p>
        <h1 style="color:#0f172a;font-size:2rem;font-weight:800;margin-bottom:0.25rem;">
            <i class="bi bi-shield-check me-2" style="color:#E2136E;"></i>Subscription Plan
        </h1>
        <p style="color:#64748b;margin:0;">Review your plan before proceeding to payment.</p>
    </div>

    {{-- PLAN CARD --}}
    <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:2rem;box-shadow:0 4px 15px rgba(0,0,0,0.06);margin-bottom:1.5rem;">

        {{-- Plan Header --}}
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
            <div>
                <p style="font-size:1.5rem;font-weight:900;color:#0f172a;margin:0;">{{ $plan['name'] }} Plan</p>
                <p style="color:#64748b;font-size:0.85rem;margin:0.2rem 0 0;">{{ $plan['billing_cycle'] }} billing</p>
            </div>
            <span style="background:rgba(226,19,110,0.1);color:#E2136E;border-radius:999px;font-size:0.75rem;font-weight:700;padding:6px 16px;">
                BDT {{ number_format($plan['amount'], 2) }} / mo
            </span>
        </div>

        {{-- Divider --}}
        <hr style="border:none;border-top:2px solid #f1f5f9;margin-bottom:1.5rem;">

        {{-- Features --}}
        <p style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;font-weight:700;margin-bottom:0.75rem;">What's included</p>
        <ul style="list-style:none;padding:0;margin:0 0 1.5rem;">
            @foreach($plan['features'] as $feature)
            <li style="display:flex;align-items:flex-start;gap:0.6rem;padding:0.45rem 0;border-bottom:1px solid #f8fafc;font-size:0.88rem;color:#374151;">
                <i class="bi bi-check-circle-fill" style="color:#E2136E;flex-shrink:0;margin-top:1px;"></i>
                {{ $feature }}
            </li>
            @endforeach
        </ul>

        {{-- Price Summary --}}
        <div style="background:#fff5f8;border:2px solid #fce4ec;border-radius:12px;padding:1rem;text-align:center;margin-bottom:1.5rem;">
            <p style="font-size:0.72rem;color:#aaa;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:0.25rem;">Total Due Today</p>
            <p style="font-size:2rem;font-weight:900;color:#E2136E;margin:0;">BDT {{ number_format($plan['amount'], 2) }}</p>
            <p style="font-size:0.78rem;color:#888;margin:0.2rem 0 0;">Auto-renews every 30 days</p>
        </div>

        {{-- CTA --}}
        @if($role === 'guardian')
            <form method="POST" action="{{ route('guardian.subscribe.confirm') }}">
                @csrf
                <button type="submit"
                    style="display:block;width:100%;background:linear-gradient(135deg,#E2136E,#c2185b);color:white;font-weight:800;border:none;border-radius:14px;padding:0.9rem 1.5rem;font-size:1rem;cursor:pointer;box-shadow:0 6px 20px rgba(226,19,110,0.3);transition:0.2s;">
                    <i class="bi bi-credit-card me-2"></i>Confirm &amp; Pay via bKash
                </button>
            </form>
        @else
            <form method="POST" action="{{ route('tutor.subscribe.confirm') }}">
                @csrf
                <button type="submit"
                    style="display:block;width:100%;background:linear-gradient(135deg,#E2136E,#c2185b);color:white;font-weight:800;border:none;border-radius:14px;padding:0.9rem 1.5rem;font-size:1rem;cursor:pointer;box-shadow:0 6px 20px rgba(226,19,110,0.3);transition:0.2s;">
                    <i class="bi bi-credit-card me-2"></i>Confirm &amp; Pay via bKash
                </button>
            </form>
        @endif
    </div>

    {{-- Cancel --}}
    <div style="text-align:center;">
        @if($role === 'guardian')
            <a href="{{ route('guardian.payment.index') }}" style="color:#94a3b8;font-size:0.85rem;text-decoration:none;">
                ← Cancel and return to payment dashboard
            </a>
        @else
            <a href="{{ route('tutor.payment.index') }}" style="color:#94a3b8;font-size:0.85rem;text-decoration:none;">
                ← Cancel and return to payment dashboard
            </a>
        @endif
    </div>

</div>
</div>

@endsection
