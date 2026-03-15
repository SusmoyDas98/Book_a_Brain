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
                    <p><span class="font-medium text-white">Phone:</span> {{ $tutorProfile->contact_no ?? 'Not added' }}</p>
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

        @if($user->role === 'TUTOR' && $tutorProfile)
            <div class="mt-8 rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-xl">
                <h2 class="mb-4 text-xl font-semibold">Tutor Details</h2>
                <div class="grid gap-4 text-slate-300 md:grid-cols-2">
                    <p>
                        <span class="font-medium text-white">Profile Picture:</span>
                        @if($tutorProfile->profile_picture)
                            <a href="{{ asset('storage/' . $tutorProfile->profile_picture) }}" target="_blank" class="text-cyan-400 hover:text-cyan-300">
                                View Profile Picture
                            </a>
                        @else
                            Not uploaded
                        @endif
                    </p>
                    <p><span class="font-medium text-white">Teaching Method:</span> {{ $tutorProfile->teaching_method ?? 'Not added' }}</p>
                    <p><span class="font-medium text-white">Availability:</span> {{ $tutorProfile->availability ?? 'Not added' }}</p>
                    <p><span class="font-medium text-white">Preferred Mediums:</span> {{ $tutorProfile->preferred_mediums ?? 'Not added' }}</p>
                    <p><span class="font-medium text-white">Preferred Subjects:</span> {{ $tutorProfile->preferred_subjects ?? 'Not added' }}</p>
                    <p><span class="font-medium text-white">Expected Salary:</span> {{ $tutorProfile->expected_salary ?? 'Not added' }}</p>
                    <p>
                        <span class="font-medium text-white">CV:</span>
                        @if($tutorProfile->cv)
                            <a href="{{ asset('storage/' . $tutorProfile->cv) }}" target="_blank" class="text-cyan-400 hover:text-cyan-300">
                                View Uploaded CV
                            </a>
                        @else
                            Not uploaded
                        @endif
                    </p>
                </div>

                <div class="mt-6 border-t border-slate-800 pt-6">
                    <h3 class="mb-4 text-lg font-semibold">Background</h3>
                    <div class="grid gap-4 text-slate-300 md:grid-cols-2">
                        <p class="md:col-span-2">
                            <span class="font-medium text-white">Educational Institutions:</span>
                            {{ $tutorProfile->educational_institutions ?? 'Not added' }}
                        </p>
                        <p class="md:col-span-2">
                            <span class="font-medium text-white">Work Experience:</span>
                            {{ $tutorProfile->work_experience ?? 'Not added' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-8 rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-xl">
                <h2 class="mb-4 text-xl font-semibold">Verification Documents</h2>
                <div class="grid gap-4 md:grid-cols-2">
                    @php
                        $nidDoc = $verificationDocuments->firstWhere('doc_type', 'NID');
                        $occupationDoc = $verificationDocuments->firstWhere('doc_type', 'OCCUPATION_CARD');
                    @endphp

                    <div class="rounded-2xl border border-slate-800 bg-slate-950 p-5">
                        <p class="font-medium text-white">NID Document</p>
                        <p class="mt-2 text-slate-300">
                            Status: {{ $nidDoc->status ?? 'Not uploaded' }}
                        </p>
                        @if($nidDoc)
                            <a href="{{ asset('storage/' . $nidDoc->file_path) }}" target="_blank" class="mt-3 inline-block text-cyan-400 hover:text-cyan-300">
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
                            <a href="{{ asset('storage/' . $occupationDoc->file_path) }}" target="_blank" class="mt-3 inline-block text-cyan-400 hover:text-cyan-300">
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
                    <p><span class="font-medium text-white">Address:</span> {{ $guardian->address ?? 'Not added' }}</p>
                    <p><span class="font-medium text-white">Latitude:</span> {{ $guardian->latitude ?? 'Not added' }}</p>
                    <p><span class="font-medium text-white">Longitude:</span> {{ $guardian->longitude ?? 'Not added' }}</p>
                    <p><span class="font-medium text-white">Number of Children:</span> {{ $guardian->number_of_children ?? 'Not added' }}</p>
                    <p><span class="font-medium text-white">Preferred Subjects:</span> {{ $guardian->preferred_subjects ?? 'Not added' }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection