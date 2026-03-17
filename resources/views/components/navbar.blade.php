<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container navbar-container">

        <!-- LOGO -->
        <a href="{{route('landing_page')}}" class="navbar-brand-custom">
            <img src="{{asset('images/book-a-brain_logo.png')}}" alt="Logo">
        </a>

        <!-- RIGHT SIDE ACTIONS -->
        <div class="navbar-actions">

            @if (Auth::check() && Auth::user()->role === "user")
                <a href="{{route('tutor_search_redirect')}}" class="btn btn-tutor-search">
                    Search Tutors
                </a>
            @endif

            <button class="btn btn-upgrade">
                UPGRADE
            </button>

            <!-- Example: You can add unlimited buttons here -->
            <!--
            <button class="btn btn-outline-dark">Dashboard</button>
            <button class="btn btn-outline-primary">Settings</button>
            -->

            <!-- PROFILE -->
            <a href="#" class="navbar-profile">
                <img src="https://i.pravatar.cc/100?img=3" alt="Profile">
            </a>

        </div>

    </div>
</nav>