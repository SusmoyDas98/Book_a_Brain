@extends('layouts.app')

@section('content')

<div style="min-height:100vh;padding:3rem 0 5rem;">
    <div class="container" style="max-width:860px;">

        {{-- Page Header --}}
        <div class="mb-4">
            <a href="{{ route('landing_page') }}" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.3em;color:#6366f1;font-weight:600;text-decoration:none;">Book-a-Brain</a>
            <h1 style="color:#0f172a;font-size:2rem;font-weight:700;margin-top:0.3rem;margin-bottom:0.4rem;">Refine Your Profile</h1>
            <p style="color:#64748b;">Update your details to maintain a polished and verified presence.</p>
        </div>
        {{-- WARNING MESSAGE --}}
        @if($completionPercentage < 100 && strtolower($user->role) === 'tutor')
            <div class="mb-3 px-4 py-3" style="background:rgba(99,102,241,0.08);border:1px solid rgba(99,102,241,0.2);border-radius:16px;">
                <p style="color:#6366f1;font-weight:700;margin:0;font-size:0.88rem;">
                    <i class="bi bi-info-circle me-2"></i>
                    Complete your profile to <strong>100%</strong> to unlock the dashboard and find tutoring jobs.
                    Currently at <strong>{{ $completionPercentage }}%</strong>.
                </p>
            </div>
        @endif
        {{-- COMPLETION BAR --}}

        <div class="bab-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.25em;color:#6366f1;font-weight:600;">
                    Profile Completion
                </span>
                <span style="font-weight:700;font-size:1rem;color:{{ $completionPercentage == 100 ? '#6366f1' : '#e2e8f0' }};">
                    @if($completionPercentage == 100)
                        <i class="bi bi-patch-check-fill me-1"></i>100% Complete
                    @else
                        {{ $completionPercentage }}%
                    @endif
                </span>
            </div>
            <div style="height:8px;background:#e2e8f0;border-radius:999px;overflow:hidden;">
                <div style="height:100%;width:{{ $completionPercentage }}%;background:linear-gradient(90deg,#06b6d4,#6366f1);border-radius:999px;"></div>
            </div>
        </div>

        {{-- FORM --}}
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- ── CORE INFORMATION ── --}}
            <div class="bab-card">
                <p class="bab-section-title"><i class="bi bi-person-circle me-2" style="color:#6366f1;"></i>Core Information</p>

                {{-- Picture row --}}
                <div class="d-flex align-items-center gap-4 mb-4">
                    @php
                        $pic = null;
                        if (strtolower($user->role) === 'tutor' && optional($tutorProfile)->profile_picture)
                            $pic = asset('storage/' . $tutorProfile->profile_picture);
                        elseif (strtolower($user->role) === 'guardian' && optional($guardian)->profile_picture)
                            $pic = asset('storage/' . $guardian->profile_picture);
                    @endphp

                    @if($pic)
                        <img src="{{ $pic }}" alt="Current picture"
                             style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid #6366f1;flex-shrink:0;">
                    @else
                        <img src="{{ asset('images/default_avatar.png') }}" alt="Default"
                             style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid #334155;flex-shrink:0;opacity:0.65;">
                    @endif

                    <div style="flex:1;">
                        <label class="bab-label">Profile Picture <span style="color:#94a3b8;">(JPG/PNG, max 2MB)</span></label>
                        <input type="file" name="profile_picture" accept=".jpg,.jpeg,.png" class="bab-file-input">
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="bab-label">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="bab-input">
                    </div>
                    <div class="col-md-6">
                        <label class="bab-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="bab-input">
                    </div>
                    <div class="col-md-6">
                        <label class="bab-label">Gender</label>
                        <select name="gender" class="bab-input">
                            <option value="">— Select —</option>
                            @php
                                $currentGender = strtolower($user->role) === 'tutor'
                                    ? optional($tutorProfile)->gender
                                    : optional($guardian)->gender;
                            @endphp
                            @foreach(['Male','Female','Other','Prefer not to say'] as $g)
                                <option value="{{ $g }}" {{ old('gender', $currentGender) === $g ? 'selected' : '' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(strtolower($user->role) === 'tutor')
                    <div class="col-md-6">
                        <label class="bab-label">Phone</label>
                        <input type="text" name="contact_no" value="{{ old('contact_no', optional($tutorProfile)->contact_no) }}" class="bab-input">
                    </div>
                    @endif
                </div>
            </div>

            {{-- ── TUTOR SECTIONS ── --}}
            @if(strtolower($user->role) === 'tutor')

                <div class="bab-card">
                    <p class="bab-section-title"><i class="bi bi-journal-text me-2" style="color:#6366f1;"></i>Teaching Details</p>
                    <div class="row g-3">
                        {{-- <div class="col-md-6">
                            <label class="bab-label">Teaching Method</label>
                            <input type="text" name="teaching_method" value="{{ old('teaching_method', is_array(optional($tutorProfile)->teaching_method) ? implode(', ', $tutorProfile->teaching_method) : optional($tutorProfile)->teaching_method) }}" class="bab-input">
                        </div> --}}
                        <div class="col-md-6">
                            <label class="bab-label">Teaching Method</label>
                        
                            <div style="margin-top: 8px; color: #000; font-weight: 500;">
                            
                                <label style="margin-right: 15px;">
                                    <input 
                                        type="checkbox" 
                                        name="teaching_method[]" 
                                        value="online"
                                        {{ in_array('online', old('teaching_method', is_array(optional($tutorProfile)->teaching_method) ? $tutorProfile->teaching_method : (optional($tutorProfile)->teaching_method ? explode(',', $tutorProfile->teaching_method) : []))) ? 'checked' : '' }}
                                        style="accent-color: #000;"
                                    >
                                    Online
                                </label>
                            
                                <label>
                                    <input 
                                        type="checkbox" 
                                        name="teaching_method[]" 
                                        value="in_person"
                                        {{ in_array('in_person', old('teaching_method', is_array(optional($tutorProfile)->teaching_method) ? $tutorProfile->teaching_method : (optional($tutorProfile)->teaching_method ? explode(',', $tutorProfile->teaching_method) : []))) ? 'checked' : '' }}
                                        style="accent-color: #000;"
                                    >
                                    In Person
                                </label>
                            
                            </div>
                        </div>                        
                        <div class="col-md-6">
                            <label class="bab-label">Availability</label>
                           <input 
                            placeholder="e.g. Mon-Fri 6-9pm, Sat 9am-1pm"
                            type="text" 
                            name="availability" 
                            value="{{ old('availability', is_array(optional($tutorProfile)->availability) ? implode(', ', $tutorProfile->availability) : optional($tutorProfile)->availability) }}" 
                            class="bab-input"
                        >
                        </div>
                        {{-- <div class="col-md-6">
                            <label class="bab-label">Preferred Mediums</label>
                            <input placeholder="use comma (',') to separate" type="text" name="preferred_mediums" value="{{ old('preferred_mediums', is_array(optional($tutorProfile)->preferred_mediums) ? implode(', ', $tutorProfile->preferred_mediums) : optional($tutorProfile)->preferred_mediums) }}" class="bab-input">
                        </div> --}}
                        <div class="col-md-6">
                            <label class="bab-label">Preferred Mediums</label>
                        
                            <div style="margin-top: 8px; color: #000; font-weight: 500;">

                                <label style="margin-right: 15px;">
                                    <input 
                                        type="checkbox" 
                                        name="preferred_mediums[]" 
                                        value="Bangla"
                                        {{ in_array('Bangla', old('preferred_mediums', is_array(optional($tutorProfile)->preferred_mediums) ? $tutorProfile->preferred_mediums : (optional($tutorProfile)->preferred_mediums ? explode(',', $tutorProfile->preferred_mediums) : []))) ? 'checked' : '' }}
                                        style="accent-color: #000;"
                                    >
                                    Bangla
                                </label>
                            
                                <label>
                                    <input 
                                        type="checkbox" 
                                        name="preferred_mediums[]" 
                                        value="English"
                                        {{ in_array('English', old('preferred_mediums', is_array(optional($tutorProfile)->preferred_mediums) ? $tutorProfile->preferred_mediums : (optional($tutorProfile)->preferred_mediums ? explode(',', $tutorProfile->preferred_mediums) : []))) ? 'checked' : '' }}
                                        style="accent-color: #000;"
                                    >
                                    English
                                </label>
                            
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="bab-label">Preferred Subjects</label>
                            <input placeholder="use comma (',') to separate"  type="text" name="preferred_subjects" value="{{ old('preferred_subjects', is_array(optional($tutorProfile)->preferred_subjects) ? implode(', ', $tutorProfile->preferred_subjects) : optional($tutorProfile)->preferred_subjects) }}" class="bab-input">
                        </div>
                        <div class="col-md-6">
                            <label class="bab-label">Expected Salary (৳)/hour</label>
                            <input placeholder="per hour" type="text" name="expected_salary" value="{{ old('expected_salary', is_array(optional($tutorProfile)->expected_salary) ? '' : optional($tutorProfile)->expected_salary) }}" class="bab-input">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="bab-label">Preferred Classes</label>
                            <input placeholder="use comma (',') to separate"  type="text" name="preferred_classes" value="{{ old('preferred_classes', is_array(optional($tutorProfile)->preferred_classes) ? implode(', ', $tutorProfile->preferred_classes) : optional($tutorProfile)->preferred_classes) }}" class="bab-input">
                        </div>
                    </div>
                </div>

                <div class="bab-card">
                    <p class="bab-section-title"><i class="bi bi-mortarboard me-2" style="color:#6366f1;"></i>Background</p>
                    <div class="mb-3">
                        <label class="bab-label">Educational Institution</label>
                        {{-- <textarea placeholder="If graduated, please mention your graduating institution."  name="educational_institutions" rows="3" class="bab-input">{{ old('educational_institutions', is_array(optional($tutorProfile)->educational_institutions) ? implode(', ', $tutorProfile->educational_institutions) : optional($tutorProfile)->educational_institutions) }}</textarea> --}}
                        
                        <div class="mb-3">
                          <label class="form-label">
                              🏫 School
                          </label>
                          <div class="input-group">
                              <span class="input-group-text">🏫</span>
                              <input 
                                  type="text" 
                                  name="educational_institutions[School]" 
                                  class="form-control"                             
                                  value="{{ old('educational_institutions.School', $tutorProfile->educational_institutions['School'] ??                           '') }}"
                              >
                          </div>
                      </div>
                      
                      <div class="mb-3">
                          <label class="form-label">
                              🎓 College
                          </label>
                          <div class="input-group">
                              <span class="input-group-text">🎓</span>
                              <input 
                                  type="text" 
                                  name="educational_institutions[College]" 
                                  class="form-control"                             
                                  value="{{ old('educational_institutions.College', $tutorProfile->educational_institutions['College'] ??                           '') }}"
                              >
                          </div>
                      </div>
                      
                      <div class="mb-3">
                          <label class="form-label">
                              🏛️ University
                          </label>
                          <div class="input-group">
                              <span class="input-group-text">🏛️</span>
                              <input 
                                  type="text" 
                                  name="educational_institutions[University]" 
                                  class="form-control"                             
                                  value="{{ old('educational_institutions.University', $tutorProfile->educational_institutions['University'] ??                           '') }}"
                              >
                          </div>
                      </div>                                     
                    </div>
                    {{-- <div>
                        <label class="bab-label">Work Experience</label>
                        <textarea placeholder=""  name="work_experience" rows="3" class="bab-input">{{ old('work_experience', is_array(optional($tutorProfile)->work_experience) ? implode(', ', $tutorProfile->work_experience) : optional($tutorProfile)->work_experience) }}</textarea>
                    </div> --}}
                    <div>
                        <label class="bab-label">Work Experience</label>
                    
                        <!-- Radio Options -->
                        <div style="margin-top: 8px;">
                        <label style="margin-right: 15px; color: #000; font-weight: 500;">
                            <input 
                                type="radio" 
                                name="work_experience[status]" 
                                value="unemployed"
                                onclick="toggleWorkExperience(false, 'Unemployed')"
                                {{ old('work_experience.status', is_array(optional($tutorProfile)->work_experience) ? ($tutorProfile->work_experience['status'] ?? '') : '') == 'unemployed' ? 'checked' : '' }}
                                style="accent-color: #000;"
                            >
                            Unemployed
                        </label>

                        <input type="hidden" id="currently_input" name="work_experience[Currently]" value="">
                             
                        
                            <label style="color: #000; font-weight: 500;">
                                <input 
                                    type="radio" 
                                    name="work_experience[status]" 
                                    value="experienced"
                                    onclick="toggleWorkExperience(true)"
                                    {{ old('work_experience.status', optional($tutorProfile)->work_experience['status'] ?? '') == 'experienced' ? 'checked' : '' }}
                                    style="accent-color: #000;"
                                >
                                Has Work Experience
                            </label>
                        </div>
                    
                        <!-- Work Experience Textarea -->
                        <div id="workExperienceBox" style="margin-top: 10px; display: none;">
                            <textarea 
                                name="work_experience[Currently]" 
                                rows="3" 
                                class="bab-input"
                                placeholder="Describe your current/ work place like 'Position at name_of_institution'..." {{ old('work_experience.Currently', is_array(optional($tutorProfile)->work_experience['Currently'] ?? null) ? implode(', ', $tutorProfile->work_experience['Currently']) : (optional($tutorProfile)->work_experience['Currently'] ?? '')) }}></textarea>
                            {{-- >{{ old('work_experience', is_array(optional($tutorProfile)->work_experience['Currently']) ? implode(', ', $tutorProfile->work_experience['Currently']) : optional($tutorProfile)->work_experience['Currently']) }}</textarea> --}}
                        </div>
                    </div>

               
                </div>

                <div class="bab-card">
                    <p class="bab-section-title"><i class="bi bi-shield-check me-2" style="color:#6366f1;"></i>Verification &amp; Documents</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="bab-label">Upload CV <span style="color:#94a3b8;">(PDF only)</span></label>
                            <input type="file" name="cv" accept=".pdf" class="bab-file-input">
                            @if(optional($tutorProfile)->cv)
                                <a href="{{ asset('storage/' . $tutorProfile->cv) }}" target="_blank"
                                   style="color:#6366f1;font-size:0.8rem;margin-top:0.4rem;display:inline-block;">
                                    <i class="bi bi-file-earmark-pdf me-1"></i>Current CV ↗
                                </a>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="bab-label">Upload NID</label>
                            <input type="file" name="nid_document" accept=".pdf,.jpg,.jpeg,.png" class="bab-file-input">
                        </div>
                        <div class="col-12">
                            <label class="bab-label">Upload Occupation Verification/ Student Id (if student) Card</label>
                            <input type="file" name="occupation_document" accept=".pdf,.jpg,.jpeg,.png" class="bab-file-input">
                        </div>
                    </div>
                </div>

            @endif

            {{-- ── GUARDIAN SECTIONS ── --}}
            @if(strtolower($user->role) === 'guardian')

                <div class="bab-card">
                    <p class="bab-section-title"><i class="bi bi-house me-2" style="color:#6366f1;"></i>Guardian Details</p>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="bab-label">Address</label>
                            <input type="text" name="address" value="{{ old('address', optional($guardian)->address) }}" class="bab-input">
                        </div>
                        <div class="col-md-6">
                            <label class="bab-label">Name</label>
                            <input type="text" name="guardian_name" value="{{ old('guardian_name', optional($guardian)->name) }}" class="bab-input">
                        </div>

                        <div class="col-md-6">
                            <label class="bab-label">Guardian Email</label>
                            <input type="email" name="email" value="{{ old('email', optional($guardian)->email ?? $user->email) }}" class="bab-input">
                        </div>                        
                        <div class="col-md-6">
                            <label class="bab-label">Contact No</label>
                            <input type="text" name="guardian_contact_no" value="{{ old('guardian_contact_no', optional($guardian)->contact_no) }}" class="bab-input">
                        </div>
                        <div class="col-md-6">
                            <label class="bab-label">Latitude</label>
                            <input type="text" name="lat" value="{{ old('lat', optional($guardian)->location['lat'] ?? '') }}" class="bab-input" placeholder="e.g. 23.8103">
                        </div>
                        <div class="col-md-6">
                            <label class="bab-label">Longitude</label>
                            <input type="text" name="lng" value="{{ old('lng', optional($guardian)->location['lng'] ?? '') }}" class="bab-input" placeholder="e.g. 90.4125">
                        </div>
                        <div class="col-12">
                            <label class="bab-label">Upload NID</label>
                            <input type="file" name="guardian_nid" accept=".pdf,.jpg,.jpeg,.png" class="bab-file-input">
                            @if(optional($guardian)->nid_card)
                                <a href="{{ asset('storage/' . $guardian->nid_card) }}" target="_blank"
                                style="color:#6366f1;font-size:0.8rem;margin-top:0.4rem;display:inline-block;">
                                    <i class="bi bi-file-earmark me-1"></i>Current NID ↗
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

            @endif
            {{-- SUBMIT --}}
            <div class="d-flex flex-wrap gap-3 align-items-center mt-2">

                {{-- Save button --}}
                <button type="submit" class="bab-btn-primary">
                    <i class="bi bi-check2 me-1"></i>Save Changes
                </button>

                {{-- Skip → goes to profile show page --}}
                <a href="{{ route('profile.show') }}" class="bab-btn-secondary">
                    Skip for now
                </a>

                {{-- Confirm → only appears when profile is 100% complete --}}
                @if($completionPercentage >= 100)
                    <form action="{{ route('profile.confirm') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="bab-btn-primary"
                            style="background:linear-gradient(90deg,#6366f1,#4f46e5);box-shadow:0 6px 20px rgba(99,102,241,0.35);">
                            <i class="bi bi-patch-check-fill me-2"></i>Confirm & Continue
                        </button>
                    </form>
                @endif

            </div>

            {{-- Error message if user tried to confirm before 100% --}}
            @if(session('error'))
                <div class="mt-3 px-4 py-3"
                    style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);color:#ef4444;border-radius:16px;">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

                    </form>
                </div>
            </div>
            <script>
                function toggleWorkExperience(isExperienced, value = '') {
                    document.getElementById('currently_input').value = value;
                }                
                function toggleWorkExperience(show) {
                    document.getElementById('workExperienceBox').style.display = show ? 'block' : 'none';
                }
            
                // On page load: show textarea if "experienced" is selected
                document.addEventListener("DOMContentLoaded", function () {
                    const selected = document.querySelector('input[name="work_status"]:checked');
                    if (selected && selected.value === 'experienced') {
                        toggleWorkExperience(true);
                    }
                });
            </script>     
            @endsection