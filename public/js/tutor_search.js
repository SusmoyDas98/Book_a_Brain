// Populate Class Select
const select = document.getElementById("classSelect");

let defaultOpt = document.createElement("option");
defaultOpt.text = "Select Class";
defaultOpt.value = ""; // Makes the value empty instead of "Select Class"
defaultOpt.disabled = true; // Prevents the user from picking it again
defaultOpt.selected = true; // Ensures it is the starting view
select.appendChild(defaultOpt);

for(let i = 1; i <= 12; i++){
  let opt = document.createElement("option");
  opt.value = i;
  opt.innerHTML = `Class ${i}`;
  select.appendChild(opt);
}

// rating-limit.js

// Wait until the DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    const minRatingInput = document.getElementById('minRatingInput');
    const maxRatingInput = document.getElementById('maxRatingInput');

    // Function to clamp value between 0 and 5
    function clampRating(input) {
        input.addEventListener('input', () => {
            let value = parseFloat(input.value);
            if (isNaN(value)) return; // ignore if empty

            if (value < 0) input.value = 0;
            if (value > 5) input.value = 5;
        });
    }

    if (minRatingInput) clampRating(minRatingInput);
    if (maxRatingInput) clampRating(maxRatingInput);
});

// salary-limit.js

document.addEventListener('DOMContentLoaded', () => {
    const minSalaryInput = document.getElementById('minSalaryInput');
    const maxSalaryInput = document.getElementById('maxSalaryInput');

    function clampSalary(input) {
        input.addEventListener('input', () => {
            let value = parseFloat(input.value);
            if (isNaN(value)) return; // ignore if empty

            if (value < 0) input.value = 0; // clamp to 0
        });
    }

    if (minSalaryInput) clampSalary(minSalaryInput);
    if (maxSalaryInput) clampSalary(maxSalaryInput);
});
// Scroll Function
function sideScroll(offset){
  document.getElementById('profileGrid').scrollBy({ left: offset, behavior: 'smooth' })
}

// Scroll to Results
function scrollToResults(){
  document.getElementById('resultsAnchor').scrollIntoView({behavior:'smooth'});
}


// Grab the button
document.getElementById('applyFilterButton').addEventListener('click', showResults);

function showResults() {
  // 1. Get basic inputs
  const location = document.getElementById('locationInput')?.value.trim() || '';
  const selectedClass = document.getElementById('classSelect').value;
  const minSalary = document.getElementById('minSalaryInput').value;
  const maxSalary = document.getElementById('maxSalaryInput').value;
  const minRating = document.getElementById('minRatingInput').value;
  const maxRating = document.getElementById('maxRatingInput').value;
  const tutorName = document.getElementById('tutorNameInput').value.trim();

  // 2. Get selected subjects
  const subjectCheckboxes = document.querySelectorAll('.subject-checkbox:checked');
  let subjects = [];
  subjectCheckboxes.forEach(cb => {
    if (cb.id === 'otherSubjectCheckbox') {
      const otherInput = document.getElementById('otherSubjectsInput').value.trim();
      if (otherInput) {
        const otherSubjects = otherInput.split(',').map(s => s.trim()).filter(s => s !== '');
        subjects = subjects.concat(otherSubjects);
      }
    } else {
      subjects.push(cb.value);
    }
  });

  // 3. Get selected mediums
  const mediumCheckboxes = document.querySelectorAll('.medium-checkbox:checked');
  let mediums = [];
  mediumCheckboxes.forEach(cb => {
    mediums.push(cb.value);
  });

  // 4. Build URL params
  const params = new URLSearchParams();
  if (selectedClass) params.append('class', selectedClass);
  if (minSalary) params.append('min_salary', minSalary);
  if (maxSalary) params.append('max_salary', maxSalary);
  if (minRating) params.append('min_rating', minRating);
  if (maxRating) params.append('max_rating', maxRating);
  if (tutorName) params.append('tutor_name', tutorName);
  if (subjects.length > 0) params.append('subjects', subjects.join(','));
  if (mediums.length > 0) params.append('mediums', mediums.join(','));

  console.log("Query Params:", params.toString());

  // Fetch  API call
  // fetch('/')
  fetch(`/api/tutor_profiles?${params.toString()}`).then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.json();
  }) .then(data => {
    const resultsSection = document.getElementById('resultsAnchor');

    if (data.length === 0) {
        resultsSection.innerHTML = `
        <h4 class="mb-3 fw-bold">Elite Matches</h4>
        <p class="text-center w-100 py-5" id = "no_tutors_found">We couldn’t find any tutors that match your search. Try changing your filters to see more results.</p>
        `;
        return;
    }
    console.log(data);
    const cards = data.map(t => {
    
        let profileImageHtml;
    
        if (t.img) {
            // profileImageHtml = `<img src="${t.img}" class="profile-img">`;
            // profileImageHtml = `<img src="{{asset(storage/)}}" class="profile-img">`;
            profileImageHtml = `<img src="/storage/${t.img}" class="profile-img">`;
            
        } 

        else if (t.gender === "Male") {
            profileImageHtml = `<img src="/images/default_male.png" class="profile-img">`;
        } 
        else if (t.gender === "Female") {
            profileImageHtml = `<img src="/images/default_female.png" class="profile-img">`;
        } 
        else {
            profileImageHtml = `
            <div class="profile-img d-flex align-items-center justify-content-center bg-primary text-white fw-bold fs-4">
                ${t.name.charAt(0).toUpperCase()}
            </div>`;
        }
    
        return `
<div class="tutor-card d-flex flex-column" style="min-height: 400px;">
    ${profileImageHtml}
    <h5 class="fw-bold mb-1">
        ${t.name}
        ${t.verification_status === 'APPROVED' ? `<span style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;border-radius:999px;font-size:0.65rem;font-weight:700;padding:2px 10px;display:inline-flex;align-items:center;gap:4px;vertical-align:middle;margin-left:6px;"><i class="bi bi-patch-check-fill"></i> Verified</span>` : ''}
    </h5>
    <p class="text-muted small mb-3">
      <i class="bi bi-mortarboard-fill me-1"></i>
      ${t.educational_institutions?.university || 'Not Specified'}
    </p>

    <!-- Main content grows -->
    <div class="flex-grow-1">
        <div class="d-flex justify-content-between border-top pt-3 mb-3">
            <span class="fw-bold text-primary">৳ ${t.expected_salary}/hr</span>
            <span class="small fw-bold text-warning">
                <i class="bi bi-star-fill"></i> ${t.rating}
            </span>
        </div>

        <div class="d-flex justify-content-start mb-1">
            <span class="fw-bold text-dark">
                Mediums: ${t.mediums?.length ? t.mediums.join(', ') : 'Not Specified'}
            </span>
        </div>

        <div class="d-flex justify-content-start mb-3">
            <span class="fw-bold text-dark">
                Subjects: ${t.subjects?.length ? t.subjects.join(', ') : 'Not Specified'}
            </span>
        </div>
  
        <div class="d-flex justify-content-start mb-3">
            <span class="fw-bold text-dark">
                Classes: ${t.classes?.length ? t.classes.join(', ') : 'Not Specified'}
            </span>
        </div>        
    </div>

    <!-- Buttons at bottom -->
    <div class="d-flex gap-2">
        <a href="/tutor_profile/${t.id}" class="btn btn-outline-primary flex-grow-1 rounded-pill d-flex align-items-center justify-content-center gap-2" title="View Profile">
            <i class="bi bi-person-circle"></i> View Profile
        </a>
        <a href="/direct_message/${t.id}" class="btn btn-primary rounded-pill px-3 d-flex align-items-center justify-content-center" title="Direct Message">
            <i class="bi bi-chat-dots-fill"></i>
        </a>
    </div>
</div>`;
    }).join("");

    resultsSection.innerHTML = `
    <h4 class="mb-3 fw-bold">Elite Matches</h4>
    <div class="scroll-wrapper" style="position:relative;">
        <div class="scroll-btn btn-prev" onclick="sideScroll(-300)">
            <i class="bi bi-chevron-left"></i>
        </div>
        <div class="scroll-btn btn-next" onclick="sideScroll(300)">
            <i class="bi bi-chevron-right"></i>
        </div>
        <div class="tutor-scroll-container" id="profileGrid">
            ${cards}
        </div>
    </div>
    `;
})
    .catch(error => console.error("Fetch error:", error));
}