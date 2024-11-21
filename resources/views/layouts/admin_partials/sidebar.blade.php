<div id="sidebar" class="d-print-none active">
    <div class="sidebar-wrapper active">

        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="{{ route('admin.dashboard') }}">
                        <img src="{{ asset('images/logo/logo_vertical.png') }}" alt="{{ config('app.name') }} Logo"
                            srcset="" style="height:30px">
                    </a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">{{ __('admin.menu') }}</li>

                <li @class([
                    'sidebar-item',
                    'active' => Route::currentRouteName() == 'admin.dashboard',
                ])>
                    <a href="{{ route('admin.dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>{{ __('admin.dashboard') }}</span>
                    </a>
                </li>

                @canany(['view domains', 'view selection lists'])
                    @php
                        $auxiliary_specifications_array = ['admin.domains.index', 'admin.enumerations.index'];
                    @endphp
                    <li @class([
                        'sidebar-item',
                        'has-sub',
                        'active' => in_array(
                            Route::currentRouteName(),
                            $auxiliary_specifications_array),
                    ])>
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-stack"></i>
                            <span>{{ __('admin.auxiliary_specifications') }}</span>
                        </a>
                        <ul @class([
                            'submenu',
                            'active' => in_array(
                                Route::currentRouteName(),
                                $auxiliary_specifications_array),
                        ])>

                            @can('view domains')
                                <li @class([
                                    'submenu-item',
                                    'active' => Route::currentRouteName() == 'admin.domains.index',
                                ])>
                                    <a href="{{ route('admin.domains.index') }}">{{ trans_choice('domain.domains', 2) }}</a>
                                </li>
                            @endcan
                            @can('view selection lists')
                                <li @class([
                                    'submenu-item',
                                    'active' => Route::currentRouteName() == 'admin.enumerations.index',
                                ])>
                                    <a
                                        href="{{ route('admin.enumerations.index') }}">{{ trans_choice('enumeration.enumerations', 2) }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @role('Super Admin')
                    @php
                        $access_control_array = [
                            'admin.users.index',
                            'admin.users.create',
                            'admin.users.edit',
                            'admin.roles.index',
                            'admin.login-history',
                            'admin.activity-history',
                        ];
                    @endphp
                    <li @class([
                        'sidebar-item',
                        'has-sub',
                        'active' => in_array(Route::currentRouteName(), $access_control_array),
                    ])>
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-people-fill"></i>
                            <span>{{ __('admin.access_control') }}</span>
                        </a>
                        <ul @class([
                            'submenu',
                            'active' => in_array(Route::currentRouteName(), $access_control_array),
                        ])>
                            <li @class([
                                'submenu-item',
                                'active' => in_array(Route::currentRouteName(), [
                                    'admin.users.index',
                                    'admin.users.create',
                                    'admin.users.edit',
                                ]),
                            ])>
                                <a href="{{ route('admin.users.index') }}">{{ trans_choice('user.users', 2) }}</a>
                            </li>
                            <li @class([
                                'submenu-item',
                                'active' => Route::currentRouteName() == 'admin.roles.index',
                            ])>
                                <a href="{{ route('admin.roles.index') }}">{{ trans_choice('role.roles', 2) }}</a>
                            </li>
                            <li @class([
                                'submenu-item',
                                'active' => Route::currentRouteName() == 'admin.login-history',
                            ])>
                                <a
                                    href="{{ route('admin.login-history') }}">{{ __('user.login_history.login_history') }}</a>
                            </li>
                            <li @class([
                                'submenu-item',
                                'active' => Route::currentRouteName() == 'admin.activity-history',
                            ])>
                                <a
                                    href="{{ route('admin.activity-history') }}">{{ __('user.activity_history.activity_history') }}</a>
                            </li>
                        </ul>
                    </li>
                @endrole

                <li @class([
                    'sidebar-item',
                    'active' => Route::currentRouteName() == 'admin.user-guide',
                ])>
                    <a href="{{ route('admin.user-guide') }}" class='sidebar-link'>
                        <i class="bi bi-journal-text"></i>
                        <span>{{ __('admin.user_guide') }}</span>
                    </a>
                </li>

            </ul>
        </div>

        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>

    </div>
</div>
