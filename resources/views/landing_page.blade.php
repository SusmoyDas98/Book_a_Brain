<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book-a-Brain | Elite Tutoring Platform</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/landing_page.css')}}">
</head>
<body>

    <div class="bg-accent"></div>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand fw-700" href="#">
                            <img src="{{ asset('images/book-a-brain_logo.png') }}" alt="website-logo" class = 'logo-image'>                
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link px-3" href="#">Tutors</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#">Process</a></li>
                    <li class="nav-item ms-lg-4"><a href="{{ route('login_or_signup_page_redirect')}}"><button class="btn btn-elite">Join Now</button></a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7" data-aos="fade-up" data-aos-duration="1000">
                    <div class="d-inline-block px-3 py-1 mb-4 rounded-pill bg-light border small fw-600 text-secondary">
                        <i class="bi bi-stars text-primary me-2"></i> Elite Academic Standards
                    </div>
                    <h1 class="hero-title">
                        Hire <b>Elite Tutors</b> for <br>
                        <span>Academic Mastery.</span>
                    </h1>
                    <p class="text-secondary fs-5 mt-4 mb-5" style="max-width: 600px; font-weight: 400; line-height: 1.8;">
                        Experience a higher standard of education. We connect ambitious students with world-class mentors through a refined, data-driven approach.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <button class="btn btn-elite">Explore Experts</button>
                        <button class="btn btn-outline-elite">Our Methodology</button>
                    </div>
                </div>

                <div class="col-lg-5" data-aos="fade-up" data-aos-delay="400">
                    <div class="image-stack-container">
                        <div class="tutor-frame frame-black">
                            <img src="{{ asset('images/female_teacher.png') }}" alt="Tutor Focus" class = 'tutor-image'>
                        </div>
                        
                        <div class="tutor-frame frame-pink">
                            <img src="{{ asset('images/male_teacher.png') }}" alt="Tutor Profile" class = 'tutor-image'>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up">
                    <div class="premium-card">
                        <div class="text-primary fs-3 mb-3"><i class="bi bi-shield-lock"></i></div>
                        <h5 class="fw-700">Vetted Excellence</h5>
                        <p class="text-secondary small mb-0">Our tutors undergo a rigorous 5-step screening process to ensure only the top 1% represent our brand.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="premium-card">
                        <div class="text-primary fs-3 mb-3"><i class="bi bi-bar-chart-line"></i></div>
                        <h5 class="fw-700">Precision Matching</h5>
                        <p class="text-secondary small mb-0">We don't just find a tutor; we find the specific mentor who aligns with your learning psychology.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="premium-card">
                        <div class="text-primary fs-3 mb-3"><i class="bi bi-globe"></i></div>
                        <h5 class="fw-700">Global Network</h5>
                        <p class="text-secondary small mb-0">Access educators from Ivy League institutions and top global research centers instantly.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 my-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-5 mb-lg-0" data-aos="fade-right">
                    <h2 class="fw-700 display-6">Our Scientific <br>Methodology</h2>
                    <p class="text-secondary mt-3">Education is a science. We treat it as such by monitoring 20+ performance metrics for every student.</p>
                </div>
                <div class="col-lg-6 offset-lg-1">
                    <div class="method-step mb-5" data-aos="fade-left">
                        <h6 class="fw-700">Diagnostic Analysis</h6>
                        <p class="small text-secondary">A comprehensive evaluation of the student's current proficiency and cognitive hurdles.</p>
                    </div>
                    <div class="method-step mb-5" data-aos="fade-left" data-aos-delay="100">
                        <h6 class="fw-700">Curated Pairing</h6>
                        <p class="small text-secondary">Tutors are hand-picked based on subject expertise and behavioral compatibility.</p>
                    </div>
                    <div class="method-step" data-aos="fade-left" data-aos-delay="200">
                        <h6 class="fw-700">Adaptive Growth</h6>
                        <p class="small text-secondary">Curriculums that evolve in real-time based on the student's pace and success rate.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row border-bottom border-secondary pb-5">
                <div class="col-lg-4 mb-4">
                    <h4 class="text-white fw-700 mb-4">Book-a-Brain</h4>
                    <p class="small">The definitive platform for professional tutoring and intellectual advancement. Engineered for those who demand excellence.</p>
                </div>
                <div class="col-lg-2 offset-lg-2 col-6">
                    <h6 class="text-white small fw-700 text-uppercase mb-4">Platform</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2">For Students</li>
                        <li class="mb-2">For Tutors</li>
                        <li class="mb-2">Quality Standards</li>
                    </ul>
                </div>
                <div class="col-lg-2 col-6">
                    <h6 class="text-white small fw-700 text-uppercase mb-4">Company</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2">Our Story</li>
                        <li class="mb-2">Contact</li>
                        <li class="mb-2">Privacy Policy</li>
                    </ul>
                </div>
            </div>
            <div class="pt-4 d-flex justify-content-between align-items-center small">
                <p class="mb-0">© 2026 Book-a-Brain. All rights reserved.</p>
                <div class="d-flex gap-3">
                    <i class="bi bi-linkedin"></i>
                    <i class="bi bi-twitter-x"></i>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true, easing: 'ease-out' });
    </script>


</body>
</html>