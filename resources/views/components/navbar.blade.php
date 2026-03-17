<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container d-flex align-items-center justify-content-between">

        <a href="{{route('landing_page')}}">
            <img src="{{asset('images/book-a-brain_logo.png')}}" style="height:40px">
        </a>

        <div class="d-flex align-items-center gap-3">
            
            <div class="d-flex align-items-center gap-2">
                @if (Auth::check() && Auth::user()->role === "user")
                    <a href="{{route('tutor_search_redirect')}}" class="btn btn-tutor_search">Search Tutors</a>
                @endif
                <button class="btn btn-upgrade">UPGRADE</button>
            </div>

            <a href="#" class="p-0">
                <img src="https://i.pravatar.cc/100?img=3" class="rounded-circle" style="height:40px; width:40px; object-fit:cover;">
            </a>
            
        </div>

    </div>
</nav>