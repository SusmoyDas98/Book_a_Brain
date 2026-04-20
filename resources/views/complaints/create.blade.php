@extends('layouts.app')

@section('content')

<div style="min-height:100vh; padding: 2.5rem 0 5rem; margin-top:40px;">
<div class="container" style="max-width:650px;">

    <div class="mb-4">
        <a href="{{ route('dashboard') }}" style="color:#6366f1;font-weight:600;font-size:0.85rem;text-decoration:none;">
            <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
        </a>
    </div>

    <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:2rem;box-shadow:0 8px 30px rgba(99,102,241,0.07);">

        <div style="text-align:center;margin-bottom:1.75rem;">
            <div style="width:56px;height:56px;background:rgba(239,68,68,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                <i class="bi bi-exclamation-triangle-fill" style="color:#ef4444;font-size:1.5rem;"></i>
            </div>
            <h2 style="color:#0f172a;font-weight:800;margin-bottom:0.3rem;">File a Complaint</h2>
            <p style="color:#64748b;font-size:0.88rem;margin:0;">
                Report an issue with a tutor or guardian. Our team will review it within 48 hours.
            </p>
        </div>

        @if($errors->any())
            <div class="mb-3 px-4 py-3" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);color:#ef4444;border-radius:16px;font-size:0.85rem;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('complaints.store') }}" method="POST">
            @csrf

            {{-- Against user - chosen from contracts --}}
            <div style="margin-bottom:1.25rem;">
                <label class="bab-label">Related Contract</label>
                <select name="contract_id" class="bab-input" id="contract-select"
                        onchange="updateAgainstUser(this)" required>
                    <option value="">— Select a contract —</option>
                    @foreach($contracts as $c)
                        <option value="{{ $c->id }}"
                                data-against="{{ $c->guardian_id === Auth::id() ? $c->tutor_id : $c->guardian_id }}">
                            {{ $c->subject }} — {{ $c->guardian_id === Auth::id() ? $c->tutor->name : $c->guardian->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <input type="hidden" name="against_user" id="against-user-input" value="">

            <div style="margin-bottom:1.25rem;">
                <label class="bab-label">Subject</label>
                <input type="text" name="subject" class="bab-input" value="{{ old('subject') }}"
                       placeholder="Brief description of the issue" required>
            </div>

            <div style="margin-bottom:1.5rem;">
                <label class="bab-label">Description</label>
                <textarea name="description" rows="5" class="bab-input" required
                          placeholder="Please describe the issue in detail...">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="bab-btn-primary" style="width:100%;text-align:center;background:linear-gradient(135deg,#ef4444,#dc2626);">
                <i class="bi bi-send me-2"></i>Submit Complaint
            </button>
        </form>
    </div>

</div>
</div>

<script>
function updateAgainstUser(select) {
    const option = select.options[select.selectedIndex];
    document.getElementById('against-user-input').value = option.dataset.against || '';
}
</script>

@endsection
