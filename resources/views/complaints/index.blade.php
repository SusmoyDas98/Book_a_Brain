@extends('layouts.app')

@section('content')
<div class="container py-5" style="margin-top:60px; max-width: 900px;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: #0f172a; font-family: 'Plus Jakarta Sans', sans-serif;">
            <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>My Complaints
        </h2>
        <a href="{{ route('complaints.create') }}" class="btn" style="background:#ef4444;color:white;font-weight:700;border-radius:12px;padding:8px 20px;">
            <i class="bi bi-plus-lg me-1"></i> File New Complaint
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="border-radius: 12px; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0" style="background: white; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); padding: 1.5rem;">
        
        @if($complaints->isEmpty())
            <div class="text-center py-5">
                <div style="width: 80px; height: 80px; background: rgba(239, 68, 68, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                    <i class="bi bi-check-circle" style="font-size: 2.5rem; color: #ef4444;"></i>
                </div>
                <h4 style="font-weight: 800; color: #0f172a;">No Complaints Filed</h4>
                <p style="color: #64748b;">You haven't submitted any complaints yet.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle" style="font-size: 0.95rem;">
                    <thead style="background: #f8fafc; color: #64748b; font-weight: 600;">
                        <tr>
                            <th class="border-0 rounded-start px-4 py-3">Date</th>
                            <th class="border-0 py-3">Subject</th>
                            <th class="border-0 py-3">Against</th>
                            <th class="border-0 py-3">Status</th>
                            <th class="border-0 rounded-end px-4 py-3 text-end">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($complaints as $complaint)
                            <tr>
                                <td class="px-4 py-3" style="color: #64748b;">{{ $complaint->created_at->format('M d, Y') }}</td>
                                <td class="py-3" style="color: #0f172a; font-weight: 600;">{{ $complaint->subject }}</td>
                                <td class="py-3">
                                    <span style="background: #f1f5f9; padding: 4px 10px; border-radius: 8px; color: #475569; font-size: 0.85rem; font-weight: 600;">
                                        {{ $complaint->accusedUser->name }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    @if($complaint->status === 'Open')
                                        <span class="badge" style="background: #fef9c3; color: #ca8a04; padding: 6px 12px; border-radius: 8px;">Open</span>
                                    @elseif($complaint->status === 'Under Review')
                                        <span class="badge" style="background: #fef3c7; color: #d97706; padding: 6px 12px; border-radius: 8px;"><i class="bi bi-search me-1"></i>Under Review</span>
                                    @elseif($complaint->status === 'Resolved')
                                        <span class="badge" style="background: #dcfce7; color: #16a34a; padding: 6px 12px; border-radius: 8px;">Resolved</span>
                                    @else
                                        <span class="badge" style="background: #f1f5f9; color: #64748b; padding: 6px 12px; border-radius: 8px;">Dismissed</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <button class="btn btn-sm" style="background: rgba(99,102,241,0.1); color: #6366f1; border-radius: 8px; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#complaintModal{{ $complaint->id }}">
                                        View
                                    </button>
                                </td>
                            </tr>

                            <!-- Complaint Modal -->
                            <div class="modal fade" id="complaintModal{{ $complaint->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content" style="border-radius: 20px; border: none;">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold">Complaint Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body pt-3 pb-4 px-4">
                                            <h6 style="color: #64748b; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Subject</h6>
                                            <p style="color: #0f172a; font-weight: 600; margin-bottom: 1.5rem;">{{ $complaint->subject }}</p>

                                            <h6 style="color: #64748b; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Description</h6>
                                            <p style="color: #334155; font-size: 0.95rem; background: #f8fafc; padding: 1rem; border-radius: 12px; line-height: 1.6; margin-bottom: 1.5rem;">
                                                {{ $complaint->description }}
                                            </p>

                                            @if($complaint->status !== 'Open' && $complaint->admin_note)
                                                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 1rem;">
                                                    <h6 style="color: #16a34a; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;"><i class="bi bi-shield-check me-1"></i> Admin Resolution Note</h6>
                                                    <p style="color: #15803d; font-size: 0.95rem; margin: 0;">{{ $complaint->admin_note }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
