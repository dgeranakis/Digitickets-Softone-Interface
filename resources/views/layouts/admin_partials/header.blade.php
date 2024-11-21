<header class="mb-2 d-print-none">
    <nav class="navbar navbar-expand navbar-light ">
        <div class="container-fluid">
            <a href="#" class="burger-btn d-block">
                <i class="bi bi-justify fs-3"></i>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link active dropdown-toggle" href="#" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-translate bi-sub fs-4 text-gray-600"></i> {{ strtoupper(appLocale()) }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end bg-white" aria-labelledby="dropdownMenuButton">
                            @foreach (config('app.locales') as $locale => $locale_name)
                                <li>
                                    <a @class(['dropdown-item', 'active' => appLocale() == $locale])
                                        href="{{ route('set-locale', $locale) }}">{{ $locale_name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>

                <div class="dropdown">
                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-menu d-flex">
                            <div class="user-name text-end me-3">
                                <h6 class="mb-0 text-gray-600">{{ Auth::user()->name }}</h6>
                                <p class="mb-0 text-sm text-gray-600">Administrator</p>
                            </div>
                            <div class="user-img d-flex align-items-center">
                                <div class="avatar avatar-md">
                                    <img src="{{ asset('/images/avatar-default.png') }}">
                                </div>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end bg-white" aria-labelledby="dropdownMenuButton">
                        <li>
                            <h6 class="dropdown-header">{{ __('auth.hello') }}, {{ strtok(Auth::user()->name, ' ') }}!
                            </h6>
                        </li>
                        <li><a href="{{ route('admin.my-profile') }}" @class([
                            'dropdown-item',
                            'active' => Route::currentRouteName() == 'admin.my-profile',
                        ])>
                                <i class="icon-mid bi bi-person me-2"></i> {{ __('auth.my_profile') }}
                            </a></li>
                        <li><a href="{{ route('admin.change-password') }}" @class([
                            'dropdown-item',
                            'active' => Route::currentRouteName() == 'admin.change-password',
                        ])>
                                <i class="icon-mid bi bi-gear me-2"></i> {{ __('auth.change_password') }}
                            </a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="icon-mid bi bi-box-arrow-left me-2"></i> {{ __('auth.logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </nav>
</header>
