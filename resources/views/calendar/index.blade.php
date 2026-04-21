@extends('layouts.app')

@section('content')

<div style="min-height:100vh; padding: 2.5rem 0 5rem; margin-top:40px;">
<div class="container" style="max-width:1000px;">

    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.3em;color:#4f46e5;font-weight:700;">Tutor Panel</p>
            <h1 style="color:#0f172a;font-size:1.8rem;font-weight:800;margin-bottom:0.3rem;">My Calendar</h1>
            <p style="color:#64748b;font-size:0.88rem;">Manage your teaching schedule and events.</p>
        </div>
        <button id="addEventBtn" style="background:#4f46e5;color:#fff;padding:0.5rem 1.2rem;border-radius:10px;border:none;font-size:0.85rem;font-weight:600;cursor:pointer;">
            <i class="bi bi-plus-lg me-1"></i>Add Event
        </button>
    </div>

    @if(session('success'))
        <div class="mb-3 px-4 py-3" style="background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.25);color:#047857;border-radius:12px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Upcoming Events Table --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;">
        <div style="padding:1rem 1.5rem;border-bottom:1px solid #f1f5f9;">
            <h2 style="font-size:1rem;font-weight:700;color:#0f172a;margin:0;">Upcoming Events</h2>
        </div>
        <div id="eventList" style="padding:1rem 1.5rem;">
            <p style="color:#94a3b8;font-size:0.88rem;text-align:center;padding:2rem 0;">Loading events…</p>
        </div>
    </div>

</div>
</div>

{{-- Add Event Modal --}}
<div id="eventModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:20px;padding:2rem;width:100%;max-width:480px;margin:1rem;">
        <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:1rem;">New Event</h3>
        <form id="eventForm">
            @csrf
            <div class="mb-3">
                <label style="font-size:0.82rem;font-weight:600;color:#334155;">Title</label>
                <input type="text" name="title" required class="form-control" style="border-radius:10px;font-size:0.88rem;">
            </div>
            <div class="mb-3">
                <label style="font-size:0.82rem;font-weight:600;color:#334155;">Date</label>
                <input type="date" name="event_date" required class="form-control" style="border-radius:10px;font-size:0.88rem;">
            </div>
            <div class="row g-2 mb-3">
                <div class="col">
                    <label style="font-size:0.82rem;font-weight:600;color:#334155;">Start Time</label>
                    <input type="time" name="start_time" class="form-control" style="border-radius:10px;font-size:0.88rem;">
                </div>
                <div class="col">
                    <label style="font-size:0.82rem;font-weight:600;color:#334155;">End Time</label>
                    <input type="time" name="end_time" class="form-control" style="border-radius:10px;font-size:0.88rem;">
                </div>
            </div>
            <div class="mb-3">
                <label style="font-size:0.82rem;font-weight:600;color:#334155;">Notes</label>
                <textarea name="notes" class="form-control" rows="2" style="border-radius:10px;font-size:0.88rem;"></textarea>
            </div>
            <div class="d-flex gap-2 justify-content-end">
                <button type="button" id="cancelBtn" style="background:#f1f5f9;color:#334155;border:none;padding:0.5rem 1.2rem;border-radius:10px;font-size:0.85rem;font-weight:600;cursor:pointer;">Cancel</button>
                <button type="submit" style="background:#4f46e5;color:#fff;border:none;padding:0.5rem 1.2rem;border-radius:10px;font-size:0.85rem;font-weight:600;cursor:pointer;">Save Event</button>
            </div>
        </form>
    </div>
</div>

<script>
const storeUrl = "{{ route('calendar.store') }}";
const eventsUrl = "{{ route('calendar.events') }}";
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
    || document.querySelector('input[name="_token"]')?.value || '';

function loadEvents() {
    fetch(eventsUrl)
        .then(r => r.json())
        .then(events => {
            const list = document.getElementById('eventList');
            if (!events.length) {
                list.innerHTML = '<p style="color:#94a3b8;font-size:0.88rem;text-align:center;padding:2rem 0;">No upcoming events.</p>';
                return;
            }
            list.innerHTML = events.map(e => `
                <div style="display:flex;justify-content:space-between;align-items:center;padding:0.75rem 0;border-bottom:1px solid #f1f5f9;">
                    <div>
                        <div style="font-weight:600;color:#0f172a;font-size:0.9rem;">${e.title}</div>
                        <div style="color:#64748b;font-size:0.78rem;">${e.start}${e.extendedProps.notes ? ' · ' + e.extendedProps.notes : ''}</div>
                    </div>
                    <button onclick="deleteEvent(${e.id})" style="background:#fee2e2;color:#ef4444;border:none;padding:0.3rem 0.7rem;border-radius:8px;font-size:0.78rem;cursor:pointer;">Delete</button>
                </div>
            `).join('');
        });
}

function deleteEvent(id) {
    if (!confirm('Delete this event?')) return;
    fetch(`/calendar/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
    }).then(() => loadEvents());
}

document.getElementById('addEventBtn').onclick = () => {
    document.getElementById('eventModal').style.display = 'flex';
};
document.getElementById('cancelBtn').onclick = () => {
    document.getElementById('eventModal').style.display = 'none';
};
document.getElementById('eventForm').onsubmit = function(e) {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(this));
    fetch(storeUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify(data)
    }).then(r => {
        if (r.ok) {
            document.getElementById('eventModal').style.display = 'none';
            this.reset();
            loadEvents();
        }
    });
};

loadEvents();
</script>

@endsection
