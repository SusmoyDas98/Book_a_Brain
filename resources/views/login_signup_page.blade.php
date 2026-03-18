<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elite Access | Book-a-Brain</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/login_signup_page.css') }}">
</head>
<body>

    <div class="bg-blob" style="top: 10%; left: 10%;"></div>
    <div class="bg-blob" style="bottom: 10%; right: 10%; animation-delay: -5s;"></div>

    <div class="auth-card">
        <img src="{{ asset('images/book-a-brain_logo.png') }}" alt="Book-a-Brain" class="brand-logo">

        <div class="nav-tabs-custom">
            <button class="btn-tab active" onclick="switchTab('login', this)">Login</button>
            <button class="btn-tab" onclick="switchTab('signup', this)">Sign Up</button>
        </div>
        
        <div id="form-container">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif                
            <div id="login-form" class="form-content">
                <div class="text-center mb-4">
                    <h4 class="fw-700 mb-1">Welcome back</h4>
                    <p class="text-muted small">Enter your credentials to access your dashboard</p>
                </div>
                <form action="{{ route('auth.store')}}" method="POST">
                    @csrf
                    <input type = 'hidden' name = 'action_type' value = 'login'>
                    <div class="input-group-elite">
                        
                        <input type="email" placeholder="Email Address" name="email" required>
                    </div>
                    <div class="input-group-elite">
                        <input type="password" placeholder="Password" name = 'password' required>                          
                    </div>

                    <div class="d-flex justify-content-between mb-4 small">
                        {{-- <label class="text-muted"><input type="checkbox" class="me-1"> Remember me</label> --}}
                        {{-- <a href="#" class="text-primary fw-600 text-decoration-none">Forgot Password?</a> --}}
                    </div>

                    <button class="btn-submit mb-4"  type = 'submit'>Log In</button>
                </form>
                </div>
            

            <div id="signup-form" class="form-content" style="display: none;">
                <div class="text-center mb-4">
                    <h4 class="fw-700 mb-1">Join the Elite</h4>
                    <p class="text-muted small">Begin your personalized learning journey</p>
                </div>
                <form action = "{{route('auth.store')}}" method = "POST">
                    @csrf
                    <input type = 'hidden' name = 'action_type' value = 'signup'>
                    <div class="input-group-elite">
                        <input type="text" placeholder="Full Name" name = 'name' required>
                                     
                    </div>
                    <div class="input-group-elite">
                        <input type="email" placeholder="Email Address" name = 'email' required>
                   
                    </div>
                    <div class="input-group-elite">
                        <input type="password" placeholder="Create Password" name = 'password' required>
                    
                    </div>
                    <div class="input-group-elite">
                         <input type="password" placeholder="Confirm Password" name = 'password_confirmation' required>
        
                    </div>
                    <button class="btn-submit mb-4" type = "submit">Get Started</button>
                </form>
            </div>
        </div>

        <div class="position-relative mb-4">
            <hr class="text-muted">
            <span class="position-absolute top-50 start-50 translate-middle bg-white px-2 small text-muted">Quick Access</span>
        </div>

        <div class="social-grid">
            <a href="/google/redirect" class="btn-social w-100 d-flex justify-content-center align-items-center">
                <i class="bi bi-google me-2"></i> Continue with Google
            </a>
        </div>

        <p class="text-center mt-4 small text-muted">
            Secured by Book-a-Brain Neural Link <i class="bi bi-patch-check-fill text-primary"></i>
        </p>
    </div>


        <script src = "{{asset('js\login_signup_page.js')}}"></script>
</body>
</html>