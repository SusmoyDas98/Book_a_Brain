<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Path | Book-a-Brain</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{asset('css/select_role.css')}}">

</head>
<body>

    <div class="paths-container">
        <div class="text-center header-area">
            <h2 class="fw-700">Select Your Role</h2>
            <p class="text-white-50 small">Remember: An account will allow any one role</p>
        </div>

        <div id="tutorSection" class="role-option tutor-path">
            <div class="parallel-lines lines-top"></div>
            <div class="parallel-lines lines-bottom"></div>
            <div class="role-circle">
                <img src="{{asset('images/teacher.png')}}" alt="Tutor">
            </div>
            <div class="role-content">
                <div class="role-title">Tutor</div>
                <p class="role-desc">For professional educators seeking best opportunities and tuition assignments.</p>
                <button class="btn-proceed">Continue as Tutor <i class="bi bi-arrow-right ms-2"></i></button>
            </div>
        </div>

        <div id="guardianSection" class="role-option guardian-path">
            <div class="parallel-lines lines-top"></div>
            <div class="parallel-lines lines-bottom"></div>
            <div class="role-circle">
                <img src="{{asset('images/tutor_seeker.png')}}" alt="Tutor_seeker">
            </div>
            <div class="role-content">
                <div class="role-title">Tutor Seeker</div>
                <p class="role-desc">For Guardians and Students looking for elite academic support and vetted mentors.</p>
                <button class="btn-proceed">Continue as Tutor Seeker <i class="bi bi-arrow-right ms-2"></i></button>
            </div>
        </div>
    </div>

    <script src = "{{asset('js/select_role.js')}}"></script>

</body>
</html>