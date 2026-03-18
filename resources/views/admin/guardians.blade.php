@extends('layouts.app')

@section('content')
@php
    $guardians = \App\Models\User::where('role','guardian')
        ->with('guardian')
        ->latest()
        ->get();
@endphp

<div style="min-height:100vh;padding:2.5rem 0 5rem;">
<div class="container" style="max-width:1100px;">

    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.3em;color:#6366f1;font-weight:700;">Admin · Book-a-Brain</p>
            <h1 style="color:#0f172a;font-size:2rem;font-weight:800;margin-bottom:0.2rem;">All Guardians</h1>
            <p style="color:#64748b;margin:0;">{{ $guardians->count() }} guardians registered on the platform.</p>
        </div>
        <a href="{{ route('dashboard') }}" style="background:#f1f5f9;color:#6366f1;font-weight:700;border-radius:12px;padding:0.6rem 1.25rem;text-decoration:none;font-size:0.85rem;border:2px solid #e2e8f0;">
            ← Back to Dashboard
        </a>
    </div>

    <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;overflow:hidden;box-shadow:0 4px 15px rgba(0,0,0,0.05);">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:2px solid #e2e8f0;">
                        <th style="padding:1rem 1.25rem;text-align:left;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Guardian</th>
                        <th style="padding:1rem 1.25rem;text-align:left;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Email</th>
                        <th style="padding:1rem 1.25rem;text-align:left;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Contact No</th>
                        <th style="padding:1rem 1.25rem;text-align:left;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Active Contracts</th>
                        <th style="padding:1rem 1.25rem;text-align:left;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guardians as $user)
                    @php
                        $g = $user->guardian;
                        $activeCount = \App\Models\TuitionContract::where('guardian_id', $user->id)->where('status','ACTIVE')->count();
                    @endphp
                    <tr style="border-bottom:1px solid #f1f5f9;transition:0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:1rem 1.25rem;">
                            <div class="d-flex align-items-center gap-3">
                                @php
                                    $pic = optional($g)->profile_picture
                                        ? asset('storage/' . $g->profile_picture)
                                        : asset('images/default_avatar.png');
                                @endphp
                                <img src="{{ $pic }}" style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid #e2e8f0;flex-shrink:0;">
                                <div>
                                    <p style="font-weight:700;color:#0f172a;margin:0;">{{ $user->name }}</p>
                                    <p style="color:#94a3b8;font-size:0.75rem;margin:0;">ID: {{ $user->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td style="padding:1rem 1.25rem;color:#64748b;">{{ $user->email }}</td>
                        <td style="padding:1rem 1.25rem;color:#64748b;">{{ optional($g)->contact_no ?: '—' }}</td>
                        <td style="padding:1rem 1.25rem;">
                            @if($activeCount > 0)
                                <span style="background:rgba(34,197,94,0.1);color:#16a34a;border-radius:999px;font-size:0.78rem;font-weight:700;padding:3px 12px;">
                                    {{ $activeCount }} active
                                </span>
                            @else
                                <span style="color:#94a3b8;font-size:0.82rem;">None</span>
                            @endif
                        </td>
                        <td style="padding:1rem 1.25rem;color:#94a3b8;font-size:0.8rem;">{{ $user->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding:3rem;text-align:center;color:#94a3b8;">No guardians registered yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>
@endsection
