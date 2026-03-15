@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-950 px-6 py-10 text-white">
    <div class="mx-auto max-w-6xl">
        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-5 py-4 text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-8">
            <p class="text-sm uppercase tracking-[0.3em] text-cyan-400">Book-a-Brain</p>
            <h1 class="mt-2 text-4xl font-semibold">Your Academic Profile</h1>
            <p class="mt-3 max-w-2xl text-slate-300">
                Manage your identity, academic depth, and professional presence with precision.
            </p>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-xl md:col-span-2">
                <h2 class="mb-4 text-xl font-semibold">Core Information</h2>
                <div class="grid gap-4 text-slate-300 md:grid-cols-2">
                    <p><span class="font-medium text-white">Name:</span> {{ $user->name }}</p>
                    <p><span class="font-medium text-white">Email:</span> {{ $user->email }}</p>
                    <p><span class="font-medium text-white">Phone:</span> {{ $user->phone }}</p>
                    <p><span class="font-medium text-white">Role:</span> {{ $user->role }}</p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-xl">
                <h2 class="mb-4 text-xl font-semibold">Profile Actions</h2>
                <p class="mb-5 text-slate-300">Keep your profile polished and trusted.</p>
                <a href="{{ route('profile.edit') }}"
                   class="inline-flex items-center rounded-2xl bg-cyan-400 px-5 py-3 font-semibold text-slate-950 transition hover:bg-cyan-300">
                    Edit Profile
                </a>

                @if($user->role === 'TUTOR')
                    <div class="mt-6">
                        <p class="mb-2 text-sm uppercase tracking-[0.2em] text-cyan-400">Completion</p>
                        <div class="h-3 w-full rounded-full bg-slate-800">
                            <div class="h-3 rounded-full bg-cyan-400" style="width: {{ $completionPercentage }}%"></div>
                        </div>
                        <p class="mt-2 text-slate-300">{{ $completionPercentage }}% complete</p>
                    </div>
                @endif
            </div>
        </div>

        @if($user->role === 'TUTOR' && $tutor)
            <div class="mt-8 rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-xl">
                <h2 class="mb-4 text-xl font-semibold">Tutor Details</h2>
                <div class="grid gap-4 text-slate-300 md:grid-cols-2">
                    <p><span class="font-medium text-white">Bio:</span> {{ $tutor->bio }}</p>
                    <p><span class="font-medium text-white">Subjects:</span> {{ $tutor->subjects }}</p>
                    <p><span class="font-medium text-white">Classes Taught:</span> {{ $tutor->classesTaught }}</p>
                    <p><span class="font-medium text-white">Medium:</span> {{ $tutor->medium }}</p>
                    <p><span class="font-medium text-white">Teaching Mode:</span> {{ $tutor->teachingMode }}</p>
                    <p><span class="font-medium text-white">Expected Salary:</span> {{ $tutor->expectedSalary }}</p>
                    <p><span class="font-medium text-white">Availability:</span> {{ $tutor->availability }}</p>
                    <p>
                        <span class="font-medium text-white">CV:</span>
                        @if($tutor->cvPath)
                            <a href="{{ asset('storage/' . $tutor->cvPath) }}" target="_blank" class="text-cyan-400 hover:text-cyan-300">
                                View Uploaded CV
                            </a>
                        @else
                            Not uploaded
                        @endif
                    </p>
                </div>

                @if($tutorProfile)
                    <div class="mt-6 border-t border-slate-800 pt-6">
                        <h3 class="mb-4 text-lg font-semibold">Academic Background</h3>
                        <div class="grid gap-4 text-slate-300 md:grid-cols-2">
                            <p><span class="font-medium text-white">Institution:</span> {{ $tutorProfile->institution }}</p>
                            <p><span class="font-medium text-white">Degree:</span> {{ $tutorProfile->degree }}</p>
                            <p><span class="font-medium text-white">Passing Year:</span> {{ $tutorProfile->passingYear }}</p>
                            <p><span class="font-medium text-white">Result:</span> {{ $tutorProfile->result }}</p>
                            <p><span class="font-medium text-white">Experience:</span> {{ $tutorProfile->experience }}</p>
                            <p><span class="font-medium text-white">Current Occupation:</span> {{ $tutorProfile->currentOccupation }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-8 rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-xl">
                <h2 class="mb-4 text-xl font-semibold">Verification Documents</h2>
                <div class="grid gap-4 md:grid-cols-2">
                    @php
                        $nidDoc = $verificationDocuments->firstWhere('docType', 'NID');
                        $occupationDoc = $verificationDocuments->firstWhere('docType', 'OCCUPATION_CARD');
                    @endphp

                    <div class="rounded-2xl border border-slate-800 bg-slate-950 p-5">
                        <p class="font-medium text-white">NID Document</p>
                        <p class="mt-2 text-slate-300">
                            Status: {{ $nidDoc->status ?? 'Not uploaded' }}
                        </p>
                        @if($nidDoc)
                            <a href="{{ asset('storage/' . $nidDoc->filePath) }}" target="_blank" class="mt-3 inline-block text-cyan-400 hover:text-cyan-300">
                                View NID File
                            </a>
                        @endif
                    </div>

                    <div class="rounded-2xl border border-slate-800 bg-slate-950 p-5">
                        <p class="font-medium text-white">Occupation Verification Card</p>
                        <p class="mt-2 text-slate-300">
                            Status: {{ $occupationDoc->status ?? 'Not uploaded' }}
                        </p>
                        @if($occupationDoc)
                            <a href="{{ asset('storage/' . $occupationDoc->filePath) }}" target="_blank" class="mt-3 inline-block text-cyan-400 hover:text-cyan-300">
                                View Occupation File
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if($user->role === 'GUARDIAN' && $guardian)
            <div class="mt-8 rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-xl">
                <h2 class="mb-4 text-xl font-semibold">Guardian Details</h2>
                <div class="grid gap-4 text-slate-300 md:grid-cols-2">
                    <p><span class="font-medium text-white">Address:</span> {{ $guardian->address }}</p>
                    <p><span class="font-medium text-white">Latitude:</span> {{ $guardian->latitude }}</p>
                    <p><span class="font-medium text-white">Longitude:</span> {{ $guardian->longitude }}</p>
                    <p><span class="font-medium text-white">Number of Children:</span> {{ $guardian->numberOfChildren }}</p>
                    <p><span class="font-medium text-white">Preferred Subjects:</span> {{ $guardian->preferredSubjects }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
