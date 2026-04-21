@extends('layouts.app')

@section('content')

<div style="min-height:100vh; padding: 2.5rem 0 5rem; margin-top:40px;">
<div class="container" style="max-width:1000px;">

    <div class="mb-4 d-flex justify-content-between align-items-start">
        <div>
            <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.3em;color:#ef4444;font-weight:700;">Admin Panel</p>
            <h1 style="color:#0f172a;font-size:1.8rem;font-weight:800;margin-bottom:0.3rem;">Analytics & Reporting</h1>
            <p style="color:#64748b;font-size:0.88rem;">Platform-wide statistics overview.</p>
        </div>
        <a href="{{ route('admin.analytics.export') }}"
           style="background:#4f46e5;color:#fff;padding:0.5rem 1.2rem;border-radius:10px;text-decoration:none;font-size:0.85rem;font-weight:600;">
            <i class="bi bi-download me-1"></i>Export CSV
        </a>
    </div>

    {{-- User Stats --}}
    <div class="row g-3 mb-4">
        @php
        $cards = [
            ['label'=>'Total Users',     'value'=>$stats['total_users'],     'icon'=>'people-fill',       'color'=>'#4f46e5'],
            ['label'=>'Tutors',          'value'=>$stats['total_tutors'],     'icon'=>'person-workspace',  'color'=>'#0ea5e9'],
            ['label'=>'Guardians',       'value'=>$stats['total_guardians'],  'icon'=>'person-heart-fill', 'color'=>'#10b981'],
        ];
        @endphp
        @foreach($cards as $card)
        <div class="col-md-4">
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:1.25rem;display:flex;align-items:center;gap:1rem;">
                <div style="width:44px;height:44px;border-radius:12px;background:{{ $card['color'] }}18;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-{{ $card['icon'] }}" style="font-size:1.2rem;color:{{ $card['color'] }};"></i>
                </div>
                <div>
                    <div style="font-size:1.5rem;font-weight:800;color:#0f172a;">{{ $card['value'] }}</div>
                    <div style="font-size:0.78rem;color:#64748b;">{{ $card['label'] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Contract Stats --}}
    <div class="row g-3 mb-4">
        @php
        $cards2 = [
            ['label'=>'Total Contracts',  'value'=>$stats['total_contracts'],  'icon'=>'file-text-fill',   'color'=>'#f59e0b'],
            ['label'=>'Active Contracts', 'value'=>$stats['active_contracts'], 'icon'=>'file-check-fill',  'color'=>'#10b981'],
        ];
        @endphp
        @foreach($cards2 as $card)
        <div class="col-md-6">
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:1.25rem;display:flex;align-items:center;gap:1rem;">
                <div style="width:44px;height:44px;border-radius:12px;background:{{ $card['color'] }}18;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-{{ $card['icon'] }}" style="font-size:1.2rem;color:{{ $card['color'] }};"></i>
                </div>
                <div>
                    <div style="font-size:1.5rem;font-weight:800;color:#0f172a;">{{ $card['value'] }}</div>
                    <div style="font-size:0.78rem;color:#64748b;">{{ $card['label'] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Review & Complaint Stats --}}
    <div class="row g-3 mb-4">
        @php
        $cards3 = [
            ['label'=>'Total Reviews',    'value'=>$stats['total_reviews'],    'icon'=>'star-fill',          'color'=>'#f59e0b'],
            ['label'=>'Average Rating',   'value'=>$stats['avg_rating'] . ' / 5', 'icon'=>'star-half',      'color'=>'#f59e0b'],
            ['label'=>'Total Complaints', 'value'=>$stats['total_complaints'], 'icon'=>'exclamation-circle', 'color'=>'#ef4444'],
            ['label'=>'Open Complaints',  'value'=>$stats['open_complaints'],  'icon'=>'flag-fill',          'color'=>'#ef4444'],
        ];
        @endphp
        @foreach($cards3 as $card)
        <div class="col-md-3">
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:1.25rem;display:flex;align-items:center;gap:1rem;">
                <div style="width:44px;height:44px;border-radius:12px;background:{{ $card['color'] }}18;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-{{ $card['icon'] }}" style="font-size:1.2rem;color:{{ $card['color'] }};"></i>
                </div>
                <div>
                    <div style="font-size:1.4rem;font-weight:800;color:#0f172a;">{{ $card['value'] }}</div>
                    <div style="font-size:0.78rem;color:#64748b;">{{ $card['label'] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
</div>

@endsection
