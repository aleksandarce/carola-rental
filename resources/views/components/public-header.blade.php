<header class="header">
    <!-- Main Header -->
    <div class="main_header">
        <div class="container">
            <div class="main_header_inner">
                <div class="main_header_logo">
                    <figure>
                        <a href="{{ route('home') }}"><img src="{{ asset('carola/assets/images/logo.png') }}" alt="Carola"></a>
                    </figure>
                </div>
                <div class="main_header_menu menu_area">
                    <!--Mobile Navigation Toggler-->
                    <div class="mobile-nav-toggler">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </div>
                    <nav class="main-menu">
                        <div class="collapse navbar-collapse show" id="navbarSupportedContent">
                            <ul class="navigation">
                                <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                                    <a href="{{ route('home') }}">Home</a>
                                </li>
                                <li class="{{ request()->routeIs('cars.*') ? 'active' : '' }}">
                                    <a href="{{ route('cars.index') }}">Cars</a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
                <div class="header_right_content">
                    <div class="link-btn">
                        @auth
                            @if (auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="btn-style-one">Dashboard</a>
                            @else
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('bookings.index') }}" class="btn-style-one">My Bookings</a>
                                    <div class="dropdown">
                                        <a href="#" class="btn-style-one dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            {{ auth()->user()->name }}
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Settings</a></li>
                                            <li>
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">Log out</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-style-one">Account</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Main Header -->

    <!-- Sticky Header-->
    <div class="sticky_header">
        <div class="container">
            <div class="main_header_inner">
                <div class="main_header_logo">
                    <figure>
                        <a href="{{ route('home') }}"><img src="{{ asset('carola/assets/images/logo.png') }}" alt="Carola"></a>
                    </figure>
                </div>
                <div class="main_header_menu menu_area">
                    <nav class="main-menu">
                        <!--Keep This Empty / Menu will come through Javascript-->
                    </nav>
                </div>
                <div class="header_right_content">
                    <div class="link-btn">
                        @auth
                            @if (auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="btn-style-one">Dashboard</a>
                            @else
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('bookings.index') }}" class="btn-style-one">My Bookings</a>
                                    <div class="dropdown">
                                        <a href="#" class="btn-style-one dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            {{ auth()->user()->name }}
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Settings</a></li>
                                            <li>
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">Log out</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-style-one">Account</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Sticky Header-->

    <!-- Mobile Menu  -->
    <div class="mobile-menu">
        <div class="menu-backdrop"></div>
        <div class="close-btn">X</div>
        <nav class="menu-box">
            <div class="nav-logo"><a href="{{ route('home') }}"><img src="{{ asset('carola/assets/images/mobile-logo.png') }}" alt="Carola"></a></div>
            <div class="menu-outer"><!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header--></div>
        </nav>
    </div>
    <!-- End Mobile Menu -->
</header>
