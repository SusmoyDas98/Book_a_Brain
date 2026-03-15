@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-950 px-6 py-10 text-white">
    <div class="mx-auto max-w-5xl rounded-3xl border border-slate-800 bg-slate-900 p-8 shadow-xl">
        <div class="mb-8">
            <p class="text-sm uppercase tracking-[0.3em] text-cyan-400">Book-a-Brain</p>
            <h1 class="mt-2 text-3xl font-semibold">Refine Your Profile</h1>
            <p class="mt-3 text-slate-300">
                Update your details to maintain a polished and verified presence on the platform.
            </p>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div>
                <h2 class="mb-4 text-xl font-semibold">Core Information</h2>
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Name</label>
                        <input type="text" name="name" value="{{ $user->name }}"
                               class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}"
                               class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Phone</label>
                        <input type="text" name="phone" value="{{ $user->phone }}"
                               class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                    </div>
                </div>
            </div>

            @if($user->role === 'TUTOR')
                <div>
                    <h2 class="mb-4 text-xl font-semibold">Tutor Details</h2>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Bio</label>
                            <input type="text" name="bio" value="{{ $tutor->bio ?? '' }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Subjects</label>
                            <input type="text" name="subjects" value="{{ $tutor->subjects ?? '' }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Classes Taught</label>
                            <input type="text" name="classesTaught" value="{{ $tutor->classesTaught ?? '' }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Medium</label>
                            <input type="text" name="medium" value="{{ $tutor->medium ?? '' }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Teaching Mode</label>
                            <input type="text" name="teachingMode" value="{{ $tutor->teachingMode ?? '' }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Expected Salary</label>
                            <input type="text" name="expectedSalary" value="{{ $tutor->expectedSalary ?? '' }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm text-slate-300">Availability</label>
                            <input type="text" name="availability" value="{{ $tutor->availability ?? '' }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="mb-4 text-xl font-semibold">Academic Background</h2>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Institution</label>
                            <input type="text" name="institution" value="{{ $tutorProfile->institution ?? '' }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Degree</label>
                            <input type="text" name="degree" value="{{ $tutorProfile->degree ?? '' }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Passing Year</label>
                            <input type="text" name="passingYear" value="{{ $tutorProfile->passingYear ?? '' }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Result</label>
                            <input type="text" name="result" value="{{ $tutorProfile->result ?? '' }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Experience</label>
                            <input type="text" name="experience" value="{{ $tutorProfile->experience ?? '' }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Current Occupation</label>
                            <input type="text" name="currentOccupation" value="{{ $tutorProfile->currentOccupation ?? '' }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="mb-4 text-xl font-semibold">Verification and Documents</h2>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Upload CV (PDF)</label>
                            <input type="file" name="cv" accept=".pdf"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-300 outline-none file:mr-4 file:rounded-xl file:border-0 file:bg-cyan-400 file:px-4 file:py-2 file:font-semibold file:text-slate-950 hover:file:bg-cyan-300">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Upload NID</label>
                            <input type="file" name="nid_document" accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-300 outline-none file:mr-4 file:rounded-xl file:border-0 file:bg-cyan-400 file:px-4 file:py-2 file:font-semibold file:text-slate-950 hover:file:bg-cyan-300">
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm text-slate-300">Upload Current Occupation Verification Card</label>
                            <input type="file" name="occupation_document" accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-300 outline-none file:mr-4 file:rounded-xl file:border-0 file:bg-cyan-400 file:px-4 file:py-2 file:font-semibold file:text-slate-950 hover:file:bg-cyan-300">
                        </div>
                    </div>
                </div>
            @endif

            @if($user->role === 'GUARDIAN')
                <div>
                    <h2 class="mb-4 text-xl font-semibold">Guardian Details</h2>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Address</label>
                            <input type="text" name="address" value="{{ $guardian->address ?? '' }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Latitude</label>
                            <input type="text" name="latitude" value="{{ $guardian->latitude ?? '' }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Longitude</label>
                            <input type="text" name="longitude" value="{{ $guardian->longitude ?? '' }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Number of Children</label>
                            <input type="text" name="numberOfChildren" value="{{ $guardian->numberOfChildren ?? '' }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm text-slate-300">Preferred Subjects</label>
                            <input type="text" name="preferredSubjects" value="{{ $guardian->preferredSubjects ?? '' }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex gap-4 pt-4">
                <button type="submit"
                        class="rounded-2xl bg-cyan-400 px-6 py-3 font-semibold text-slate-950 transition hover:bg-cyan-300">
                    Update Profile
                </button>

                <a href="{{ route('profile.show') }}"
                   class="rounded-2xl border border-slate-700 px-6 py-3 font-semibold text-white transition hover:bg-slate-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
