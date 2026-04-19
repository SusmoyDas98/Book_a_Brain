@extends('layouts.app')

@section('content')
{{-- <x-navbar/> --}}
@php
    $totalUsers    = \App\Models\User::count();
    $totalTutors   = \App\Models\User::where('role','tutor')->count();
    $totalGuardians= \App\Models\User::where('role','guardian')->count();
    $pendingDocs   = \App\Models\VerificationDocument::where('status','PENDING')->with('tutor')->get();
    $recentUsers   = \App\Models\User::latest()->take(8)->get();
    $totalContracts= \App\Models\TuitionContract::count();
    $activeContracts = \App\Models\TuitionContract::where('status','ACTIVE')->count();

    // Monthly user registrations for chart (last 6 months)
    $chartData = [];
    for ($i = 5; $i >= 0; $i--) {
        $month = now()->subMonths($i);
        $chartData[] = [
            'label' => $month->format('M'),
            'count' => \App\Models\User::whereYear('created_at', $month->year)
                                       ->whereMonth('created_at', $month->month)
                                       ->count(),
        ];
    }
@endphp

<div style="min-height:100vh; padding: 2.5rem 0 5rem;">
<div class="container" style="max-width:1100px;margin-top:80px;">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.3em;color:#6366f1;font-weight:700;">Book-a-Brain</p>
            <h1 style="color:#0f172a;font-size:2rem;font-weight:800;margin-bottom:0.25rem;">
                Admin Dashboard 🛡️
            </h1>
            <p style="color:#64748b;margin:0;">Platform overview and management tools.</p>
        </div>
        @if($pendingDocs->count())
            <div style="background:rgba(239,68,68,0.08);border:2px solid rgba(239,68,68,0.2);border-radius:16px;padding:0.75rem 1.25rem;">
                <p style="color:#ef4444;font-weight:700;font-size:0.85rem;margin:0;">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ $pendingDocs->count() }} verification{{ $pendingDocs->count() > 1 ? 's' : '' }} pending review
                </p>
            </div>
        @endif
        <a href="{{ route('admin.payment.index') }}"
           style="background:{{ request()->routeIs('admin.payment.index') ? 'linear-gradient(135deg,#6366f1,#4f46e5)' : 'white' }};color:{{ request()->routeIs('admin.payment.index') ? 'white' : '#6366f1' }};font-weight:700;border:2px solid #6366f1;border-radius:14px;padding:0.7rem 1.4rem;text-decoration:none;font-size:0.88rem;{{ request()->routeIs('admin.payment.index') ? 'box-shadow:0 6px 20px rgba(99,102,241,0.3);' : '' }}">
            <i class="bi bi-credit-card me-2"></i>Payment &amp; Tuition Fees
        </a>
    </div>

    {{-- STATS CARDS --}}
    <div class="row g-3 mb-4">
        @foreach([
            ['value' => $totalUsers,    'label' => 'Total Users',       'icon' => 'bi-people-fill',            'color' => '#6366f1', 'bg' => 'rgba(99,102,241,0.1)',  'href' => '#'],
            ['value' => $totalTutors,   'label' => 'Tutors',            'icon' => 'bi-mortarboard-fill',       'color' => '#0284c7', 'bg' => 'rgba(14,165,233,0.1)',  'href' => route('admin.tutors')],
            ['value' => $totalGuardians,'label' => 'Guardians',         'icon' => 'bi-house-heart-fill',       'color' => '#16a34a', 'bg' => 'rgba(34,197,94,0.1)',   'href' => route('admin.guardians')],
            ['value' => $activeContracts,'label' => 'Active Contracts', 'icon' => 'bi-file-earmark-check-fill','color' => '#d97706', 'bg' => 'rgba(245,158,11,0.1)',  'href' => route('admin.contracts')],
        ] as $stat)
        <div class="col-md-3 col-6">
            <a href="{{ $stat['href'] }}" style="text-decoration:none;">
                <div style="background:white;border:2px solid #e2e8f0;border-radius:20px;padding:1.25rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);transition:0.2s;" onmouseover="this.style.borderColor='#6366f1';this.style.transform='translateY(-3px)'" onmouseout="this.style.borderColor='#e2e8f0';this.style.transform='translateY(0)'">
                    <div style="width:42px;height:42px;background:{{ $stat['bg'] }};border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
                        <i class="bi {{ $stat['icon'] }}" style="color:{{ $stat['color'] }};font-size:1.2rem;"></i>
                    </div>
                    <p style="font-size:1.8rem;font-weight:800;color:#0f172a;margin:0;line-height:1;">{{ $stat['value'] }}</p>
                    <p style="color:#64748b;font-size:0.8rem;margin:0.2rem 0 0;font-weight:500;">{{ $stat['label'] }}</p>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    <div class="row g-4">

        {{-- LEFT COLUMN --}}
        <div class="col-lg-7">

            {{-- ANALYTICS CHART --}}
            <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);margin-bottom:1rem;">
                <p style="font-weight:800;color:#0f172a;font-size:1rem;margin-bottom:1.25rem;">
                    <i class="bi bi-bar-chart me-2" style="color:#6366f1;"></i>User Registrations (Last 6 Months)
                </p>
                @php $maxCount = max(array_column($chartData, 'count')) ?: 1; @endphp
                <div style="display:flex;align-items:flex-end;gap:10px;height:140px;padding-bottom:0.5rem;">
                    @foreach($chartData as $item)
                        @php $barHeight = max(($item['count'] / $maxCount) * 120, 4); @endphp
                        <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:6px;">
                            <span style="font-size:0.72rem;color:#64748b;font-weight:600;">{{ $item['count'] }}</span>
                            <div style="width:100%;height:{{ $barHeight }}px;background:linear-gradient(180deg,#6366f1,#4f46e5);border-radius:8px 8px 4px 4px;transition:0.3s;" onmouseover="this.style.background='linear-gradient(180deg,#4f46e5,#3730a3)'" onmouseout="this.style.background='linear-gradient(180deg,#6366f1,#4f46e5)'"></div>
                            <span style="font-size:0.7rem;color:#94a3b8;font-weight:600;">{{ $item['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- USER MANAGEMENT TABLE --}}
            <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p style="font-weight:800;color:#0f172a;font-size:1rem;margin:0;">
                        <i class="bi bi-people me-2" style="color:#6366f1;"></i>Recent Users
                    </p>
                    <span style="color:#94a3b8;font-size:0.78rem;">Last {{ $recentUsers->count() }} registered</span>
                </div>
                <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;font-size:0.83rem;">
                        <thead>
                            <tr style="border-bottom:2px solid #f1f5f9;">
                                <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Name</th>
                                <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Email</th>
                                <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Role</th>
                                <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentUsers as $u)
                            <tr style="border-bottom:1px solid #f8fafc;transition:0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                <td style="padding:0.65rem 0.75rem;font-weight:600;color:#0f172a;">{{ $u->name }}</td>
                                <td style="padding:0.65rem 0.75rem;color:#64748b;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $u->email }}</td>
                                <td style="padding:0.65rem 0.75rem;">
                                    @if($u->role === 'tutor')
                                        <span style="background:rgba(14,165,233,0.1);color:#0284c7;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Tutor</span>
                                    @elseif($u->role === 'guardian')
                                        <span style="background:rgba(34,197,94,0.1);color:#16a34a;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Guardian</span>
                                    @elseif($u->role === 'admin')
                                        <span style="background:rgba(99,102,241,0.1);color:#6366f1;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Admin</span>
                                    @else
                                        <span style="background:#f1f5f9;color:#94a3b8;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">—</span>
                                    @endif
                                </td>
                                <td style="padding:0.65rem 0.75rem;color:#94a3b8;font-size:0.78rem;">{{ $u->created_at->format('d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="col-lg-5">

            {{-- PENDING VERIFICATIONS --}}
            <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);margin-bottom:1rem;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p style="font-weight:800;color:#0f172a;font-size:1rem;margin:0;">
                        <i class="bi bi-shield-check me-2" style="color:#6366f1;"></i>Pending Verifications
                    </p>
                    @if($pendingDocs->count())
                        <span style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:999px;font-size:0.72rem;font-weight:700;padding:2px 10px;">{{ $pendingDocs->count() }} pending</span>
                    @endif
                </div>
                @forelse($pendingDocs->take(5) as $doc)
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:0.85rem 1rem;margin-bottom:0.6rem;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p style="font-weight:700;color:#0f172a;font-size:0.85rem;margin:0 0 0.2rem;">{{ $doc->tutor->name }}</p>
                                <p style="color:#64748b;font-size:0.77rem;margin:0;">
                                    {{ $doc->doc_type === 'NID' ? 'National ID' : 'Occupation Card' }}
                                    &nbsp;·&nbsp; {{ $doc->uploaded_at->format('d M Y') }}
                                </p>
                            </div>
                            <div class="d-flex gap-1">
                                <form action="{{ route('admin.verify.approve', $doc->tutor_id) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" style="background:rgba(34,197,94,0.1);color:#16a34a;border:1px solid rgba(34,197,94,0.3);border-radius:8px;padding:3px 10px;font-size:0.72rem;font-weight:700;cursor:pointer;">
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.verify.reject', $doc->tutor_id) }}" method="POST" class="m-0">
                                    @csrf
                                    <input type="hidden" name="reason" value="Documents invalid. Please re-upload clear copies.">
                                    <button type="submit" style="background:rgba(239,68,68,0.08);color:#ef4444;border:1px solid rgba(239,68,68,0.2);border-radius:8px;padding:3px 10px;font-size:0.72rem;font-weight:700;cursor:pointer;">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                           style="color:#6366f1;font-size:0.77rem;font-weight:600;text-decoration:none;margin-top:0.4rem;display:inline-block;">
                            <i class="bi bi-eye me-1"></i>View Document ↗
                        </a>
                    </div>
                @empty
                    <div style="text-align:center;padding:1.5rem 0;">
                        <i class="bi bi-shield-check" style="font-size:2rem;color:#86efac;"></i>
                        <p style="color:#94a3b8;font-size:0.83rem;margin:0.5rem 0 0;">All documents reviewed ✓</p>
                    </div>
                @endforelse
            </div>

            {{-- RECENT JOBS/CONTRACTS --}}
            <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p style="font-weight:800;color:#0f172a;font-size:1rem;margin:0;">
                        <i class="bi bi-briefcase me-2" style="color:#6366f1;"></i>Recent Contracts
                    </p>
                    <span style="color:#94a3b8;font-size:0.78rem;">{{ $totalContracts }} total</span>
                </div>
                @php $recentContracts = \App\Models\TuitionContract::with(['guardian','tutor'])->latest()->take(5)->get(); @endphp
                @forelse($recentContracts as $c)
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:0.65rem 0;border-bottom:1px solid #f1f5f9;">
                        <div>
                            <p style="font-size:0.83rem;font-weight:600;color:#0f172a;margin:0;">{{ $c->tutor->name }} ← {{ $c->guardian->name }}</p>
                            <p style="font-size:0.75rem;color:#94a3b8;margin:0;">{{ $c->subject }} · {{ $c->start_date->format('d M Y') }}</p>
                        </div>
                        @if($c->status === 'ACTIVE')
                            <span style="background:rgba(34,197,94,0.1);color:#16a34a;border-radius:999px;font-size:0.68rem;font-weight:700;padding:2px 8px;">ACTIVE</span>
                        @elseif($c->status === 'PENDING')
                            <span style="background:rgba(245,158,11,0.1);color:#d97706;border-radius:999px;font-size:0.68rem;font-weight:700;padding:2px 8px;">PENDING</span>
                        @else
                            <span style="background:#f1f5f9;color:#94a3b8;border-radius:999px;font-size:0.68rem;font-weight:700;padding:2px 8px;">ENDED</span>
                        @endif
                    </div>
                @empty
                    <p style="color:#94a3b8;font-size:0.83rem;text-align:center;padding:1rem 0;margin:0;">No contracts yet.</p>
                @endforelse
            </div>

        </div>
    </div>

</div>
</div>

@endsection
