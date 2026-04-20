@extends('layouts.app')

@section('content')
{{-- <x-navbar/> --}}
@php
    $user             = Auth::user();
    $guardian         = \App\Models\Guardian::where('guardian_id', $user->id)->first();
    $activeContracts  = \App\Models\TuitionContract::where('guardian_id', $user->id)->where('status', 'ACTIVE')->with('tutor')->get();
    $pendingContracts = \App\Models\TuitionContract::where('guardian_id', $user->id)->where('status', 'PENDING')->count();
    $totalHired       = \App\Models\TuitionContract::where('guardian_id', $user->id)->whereIn('status', ['ACTIVE','ENDED'])->count();
    $allContracts     = \App\Models\TuitionContract::where('guardian_id', $user->id)->with('tutor')->latest()->take(5)->get();
@endphp

<div style="min-height:100vh; padding: 2.5rem 0 5rem;">
<div class="container" style="max-width:1100px; margin-top:40px;">

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div style="background:#f0fdf4;border:2px solid #bbf7d0;color:#16a34a;border-radius:14px;padding:0.9rem 1.25rem;margin-bottom:1.25rem;font-weight:600;font-size:0.88rem;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#fef2f2;border:2px solid #fecaca;color:#ef4444;border-radius:14px;padding:0.9rem 1.25rem;margin-bottom:1.25rem;font-weight:600;font-size:0.88rem;">
            <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
        </div>
    @endif
    @if(session('info'))
        <div style="background:#eff6ff;border:2px solid #bfdbfe;color:#3b82f6;border-radius:14px;padding:0.9rem 1.25rem;margin-bottom:1.25rem;font-weight:600;font-size:0.88rem;">
            <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
        </div>
    @endif

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.3em;color:#6366f1;font-weight:700;">Book-a-Brain</p>
            <h1 style="color:#0f172a;font-size:2rem;font-weight:800;margin-bottom:0.25rem;">
                Welcome back, {{ $user->name }} 👋
            </h1>
            <p style="color:#64748b;margin:0;">Here's what's happening with your tutoring today.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('tutor_search_redirect') }}"
               style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;font-weight:700;border:none;border-radius:14px;padding:0.7rem 1.4rem;text-decoration:none;font-size:0.88rem;box-shadow:0 6px 20px rgba(99,102,241,0.3);">
                <i class="bi bi-search me-2"></i>Find Tutor
            </a>
            <a href="{{ route('contracts.guardian') }}"
               style="background:white;color:#6366f1;font-weight:700;border:2px solid #6366f1;border-radius:14px;padding:0.7rem 1.4rem;text-decoration:none;font-size:0.88rem;">
                <i class="bi bi-file-earmark-text me-2"></i>My Contracts
            </a>
            <a href="{{ route('guardian.payment.index') }}"
               style="background:{{ request()->routeIs('guardian.payment.index') ? 'linear-gradient(135deg,#6366f1,#4f46e5)' : 'white' }};color:{{ request()->routeIs('guardian.payment.index') ? 'white' : '#6366f1' }};font-weight:700;border:2px solid #6366f1;border-radius:14px;padding:0.7rem 1.4rem;text-decoration:none;font-size:0.88rem;{{ request()->routeIs('guardian.payment.index') ? 'box-shadow:0 6px 20px rgba(99,102,241,0.3);' : '' }}">
                <i class="bi bi-credit-card me-2"></i>Payment &amp; Tuition Fees
            </a>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div style="background:white;border:2px solid #e2e8f0;border-radius:20px;padding:1.25rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);transition:0.2s;" onmouseover="this.style.borderColor='#6366f1';this.style.transform='translateY(-3px)'" onmouseout="this.style.borderColor='#e2e8f0';this.style.transform='translateY(0)'">
                <div style="width:42px;height:42px;background:rgba(99,102,241,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
                    <i class="bi bi-person-check-fill" style="color:#6366f1;font-size:1.2rem;"></i>
                </div>
                <p style="font-size:1.8rem;font-weight:800;color:#0f172a;margin:0;line-height:1;">{{ $totalHired }}</p>
                <p style="color:#64748b;font-size:0.8rem;margin:0.2rem 0 0;font-weight:500;">Tutors Hired</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div style="background:white;border:2px solid #e2e8f0;border-radius:20px;padding:1.25rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);transition:0.2s;" onmouseover="this.style.borderColor='#6366f1';this.style.transform='translateY(-3px)'" onmouseout="this.style.borderColor='#e2e8f0';this.style.transform='translateY(0)'">
                <div style="width:42px;height:42px;background:rgba(34,197,94,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
                    <i class="bi bi-activity" style="color:#16a34a;font-size:1.2rem;"></i>
                </div>
                <p style="font-size:1.8rem;font-weight:800;color:#0f172a;margin:0;line-height:1;">{{ $activeContracts->count() }}</p>
                <p style="color:#64748b;font-size:0.8rem;margin:0.2rem 0 0;font-weight:500;">Active Contracts</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div style="background:white;border:2px solid #e2e8f0;border-radius:20px;padding:1.25rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);transition:0.2s;" onmouseover="this.style.borderColor='#6366f1';this.style.transform='translateY(-3px)'" onmouseout="this.style.borderColor='#e2e8f0';this.style.transform='translateY(0)'">
                <div style="width:42px;height:42px;background:rgba(245,158,11,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
                    <i class="bi bi-hourglass-split" style="color:#d97706;font-size:1.2rem;"></i>
                </div>
                <p style="font-size:1.8rem;font-weight:800;color:#0f172a;margin:0;line-height:1;">{{ $pendingContracts }}</p>
                <p style="color:#64748b;font-size:0.8rem;margin:0.2rem 0 0;font-weight:500;">Pending Requests</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div style="background:white;border:2px solid #e2e8f0;border-radius:20px;padding:1.25rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);transition:0.2s;" onmouseover="this.style.borderColor='#6366f1';this.style.transform='translateY(-3px)'" onmouseout="this.style.borderColor='#e2e8f0';this.style.transform='translateY(0)'">
                <div style="width:42px;height:42px;background:rgba(14,165,233,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
                    <i class="bi bi-currency-dollar" style="color:#0284c7;font-size:1.2rem;"></i>
                </div>
                <p style="font-size:1.8rem;font-weight:800;color:#0f172a;margin:0;line-height:1;">
                    ৳{{ number_format($activeContracts->sum('salary')) }}
                </p>
                <p style="color:#64748b;font-size:0.8rem;margin:0.2rem 0 0;font-weight:500;">Monthly Spend</p>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- ACTIVE CONTRACTS --}}
        <div class="col-lg-7">
            <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p style="font-weight:800;color:#0f172a;font-size:1rem;margin:0;">
                        <i class="bi bi-file-earmark-text me-2" style="color:#6366f1;"></i>Active Contracts
                    </p>
                    <a href="{{ route('contracts.guardian') }}" style="color:#6366f1;font-size:0.8rem;font-weight:600;text-decoration:none;">View all →</a>
                </div>

                @forelse($activeContracts as $contract)
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:16px;padding:1rem;margin-bottom:0.75rem;transition:0.2s;" onmouseover="this.style.borderColor='#6366f1'" onmouseout="this.style.borderColor='#e2e8f0'">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p style="font-weight:700;color:#0f172a;margin:0 0 0.2rem;">{{ $contract->tutor->name }}</p>
                                <p style="color:#64748b;font-size:0.82rem;margin:0;">
                                    <i class="bi bi-book me-1"></i>{{ $contract->subject }}
                                    &nbsp;·&nbsp;
                                    ৳{{ number_format($contract->salary) }}/mo
                                </p>
                            </div>
                            <a href="{{ route('contracts.show', $contract) }}"
                               style="background:rgba(99,102,241,0.08);color:#6366f1;border-radius:10px;padding:5px 12px;font-size:0.78rem;font-weight:700;text-decoration:none;white-space:nowrap;">
                                View →
                            </a>
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:2rem 0;">
                        <i class="bi bi-inbox" style="font-size:2rem;color:#cbd5e1;"></i>
                        <p style="color:#94a3b8;margin-top:0.75rem;font-size:0.85rem;">No active contracts yet.</p>
                        <a href="{{ route('tutor_search_redirect') }}" style="color:#6366f1;font-weight:600;font-size:0.85rem;text-decoration:none;">Find a tutor →</a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="col-lg-5">

            {{-- QUICK LINKS --}}
            <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);margin-bottom:1rem;">
                <p style="font-weight:800;color:#0f172a;font-size:1rem;margin-bottom:1rem;">
                    <i class="bi bi-lightning-charge me-2" style="color:#6366f1;"></i>Quick Actions
                </p>
                <div class="d-flex flex-column gap-2">
                    @foreach([
                        ['route' => 'tutor_search_redirect', 'icon' => 'bi-search', 'label' => 'Find a Tutor', 'sub' => 'Browse verified tutors'],
                        ['route' => 'contracts.guardian', 'icon' => 'bi-file-earmark-text', 'label' => 'My Contracts', 'sub' => 'View all contracts'],
                        ['route' => 'profile.edit', 'icon' => 'bi-person-circle', 'label' => 'Edit Profile', 'sub' => 'Update your details'],
                        ['route' => 'profile.show', 'icon' => 'bi-eye', 'label' => 'View Profile', 'sub' => 'See your public profile'],
                    ] as $link)
                        <a href="{{ route($link['route']) }}"
                           style="display:flex;align-items:center;gap:0.75rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:0.75rem 1rem;text-decoration:none;transition:0.2s;"
                           onmouseover="this.style.borderColor='#6366f1';this.style.background='rgba(99,102,241,0.05)'"
                           onmouseout="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc'">
                            <div style="width:36px;height:36px;background:rgba(99,102,241,0.1);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi {{ $link['icon'] }}" style="color:#6366f1;"></i>
                            </div>
                            <div>
                                <p style="color:#0f172a;font-weight:700;font-size:0.85rem;margin:0;">{{ $link['label'] }}</p>
                                <p style="color:#94a3b8;font-size:0.75rem;margin:0;">{{ $link['sub'] }}</p>
                            </div>
                            <i class="bi bi-chevron-right ms-auto" style="color:#cbd5e1;font-size:0.75rem;"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- PAYMENT HISTORY --}}
            <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p style="font-weight:800;color:#0f172a;font-size:1rem;margin:0;">
                        <i class="bi bi-receipt me-2" style="color:#6366f1;"></i>Payment History
                    </p>
                    <a href="{{ route('guardian.payment.index') }}" style="color:#6366f1;font-size:0.8rem;font-weight:600;text-decoration:none;">View all →</a>
                </div>
                @foreach($activeContracts->take(3) as $c)
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:0.6rem 0;border-bottom:1px solid #f1f5f9;">
                        <div>
                            <p style="font-size:0.83rem;font-weight:600;color:#0f172a;margin:0;">{{ $c->tutor->name }}</p>
                            <p style="font-size:0.75rem;color:#94a3b8;margin:0;">{{ $c->start_date->format('M Y') }}</p>
                        </div>
                        <span style="font-weight:700;color:#16a34a;font-size:0.85rem;">৳{{ number_format($c->salary) }}</span>
                    </div>
                @endforeach
                @if($activeContracts->isEmpty())
                    <p style="color:#94a3b8;font-size:0.83rem;text-align:center;padding:1rem 0;margin:0;">No payment records yet.</p>
                @endif
            </div>

        </div>
    </div>

</div>
</div>

@endsection
