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
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Phone</label>
                        <input type="text" name="contact_no" value="{{ old('contact_no', $tutorProfile->contact_no ?? '') }}"
                               class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                    </div>
                </div>
            </div>

            @if($user->role === 'TUTOR')
                <div>
                    <h2 class="mb-4 text-xl font-semibold">Tutor Details</h2>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Profile Picture</label>
                            <input type="file" name="profile_picture" accept=".jpg,.jpeg,.png"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-300 outline-none file:mr-4 file:rounded-xl file:border-0 file:bg-cyan-400 file:px-4 file:py-2 file:font-semibold file:text-slate-950 hover:file:bg-cyan-300">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Teaching Method</label>
                            <input type="text" name="teaching_method" value="{{ old('teaching_method', $tutorProfile->teaching_method ?? '') }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Availability</label>
                            <input type="text" name="availability" value="{{ old('availability', $tutorProfile->availability ?? '') }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Preferred Mediums</label>
                            <input type="text" name="preferred_mediums" value="{{ old('preferred_mediums', $tutorProfile->preferred_mediums ?? '') }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Preferred Subjects</label>
                            <input type="text" name="preferred_subjects" value="{{ old('preferred_subjects', $tutorProfile->preferred_subjects ?? '') }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Expected Salary</label>
                            <input type="text" name="expected_salary" value="{{ old('expected_salary', $tutorProfile->expected_salary ?? '') }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="mb-4 text-xl font-semibold">Background</h2>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm text-slate-300">Educational Institutions</label>
                            <textarea name="educational_institutions"
                                      class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400"
                                      rows="4">{{ old('educational_institutions', $tutorProfile->educational_institutions ?? '') }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm text-slate-300">Work Experience</label>
                            <textarea name="work_experience"
                                      class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400"
                                      rows="4">{{ old('work_experience', $tutorProfile->work_experience ?? '') }}</textarea>
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
                            <input type="text" name="address" value="{{ old('address', $guardian->address ?? '') }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Latitude</label>
                            <input type="text" name="latitude" value="{{ old('latitude', $guardian->latitude ?? '') }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Longitude</label>
                            <input type="text" name="longitude" value="{{ old('longitude', $guardian->longitude ?? '') }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Number of Children</label>
                            <input type="text" name="number_of_children" value="{{ old('number_of_children', $guardian->number_of_children ?? '') }}"
                                   class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-400">
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm text-slate-300">Preferred Subjects</label>
                            <input type="text" name="preferred_subjects" value="{{ old('preferred_subjects', $guardian->preferred_subjects ?? '') }}"
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