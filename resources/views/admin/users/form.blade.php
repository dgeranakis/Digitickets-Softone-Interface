<div class="row">
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label for="name">{{ __('user.name.title') }}</label>
            <input type="text" id="name" name="name" class="form-control"
                {{ isset($show) && $show ? 'disabled' : '' }}
                placeholder="{{ isset($show) && $show ? '' : __('user.name.placeholder') }}"
                value="{{ old('name', isset($user) ? optional($user)->name : '') }}" required autocomplete="name"
                autofocus>
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="form-group">
            <label for="email">{{ __('user.email.title') }}</label>
            @if (isset($show) && $show && isset($user) && filled($optional($user)->email))
                <div class="form-control" disabled style="background-color: #e9ecef;"><a
                        href="mailto:{{ $user->email }}">{{ $user->email }}</a></div>
            @else
                <input type="email" id="email" name="email" class="form-control"
                    {{ isset($show) && $show ? 'disabled' : '' }}
                    placeholder="{{ isset($show) && $show ? '' : __('user.email.placeholder') }}"
                    value="{{ old('email', isset($user) ? optional($user)->email : '') }}" required
                    autocomplete="email">
            @endif
        </div>
    </div>

    @if (!isset($show) || !$show)
        <div class="col-md-6 col-12 d-print-none">
            <label for="password">{{ __('auth.password') }}</label>
            <input type="password" name="password" class="form-control" id="password" autocomplete="new-password"
                aria-describedby="passwordHelpBlock">
            <p id="passwordHelpBlock" class="form-text text-muted">
                <small>{{ __('auth.password_strength_txt') }}</small>
            </p>
        </div>
        <div class="col-md-6 col-12 d-print-none">
            <label for="password_confirmation">{{ __('auth.password_confirmation') }}</label>
            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation"
                autocomplete="new-password">
        </div>
    @endif

    <div class="col-md-6 col-12">
        <label for="roles">{{ __('user.roles.title') }}</label>
        @php
            $roles_value = old('roles', isset($user) ? $user->roles()->pluck('name', 'id') : collect([]));
        @endphp
        <select class="form-select selectPicker" id="roles" name="roles" multiple data-select-actions="true"
            {{ isset($show) && $show ? 'disabled' : '' }}>
            <option value="">{{ __('user.roles.placeholder') }}</option>
            @foreach ($roles as $role)
                <option value="{{ $role['value'] }}" {{ $roles_value->contains($role['value']) ? 'selected' : '' }}>
                    {{ $role['value'] }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 col-12">
        <label for="lines_per_page">{{ __('user.lines_per_page.title') }}</label>
        @php
            $lines_per_page_value = old('lines_per_page', isset($user) ? $user->lines_per_page : '');
            $options = [10, 25, 50, 75, 100];
        @endphp
        <select class="form-select selectPicker" id="lines_per_page" name="lines_per_page"
            {{ isset($show) && $show ? 'disabled' : '' }}>
            @foreach ($options as $option)
                <option value="{{ $option }}" @if ($lines_per_page_value == $option) selected @endif>
                    {{ $option }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-12 mt-3">
        <ul class="list-unstyled mb-0">
            <li class="d-inline-block me-2 mb-1">
                <div class="form-check">
                    <div class="checkbox">
                        @php
                            $active = old('active', isset($user) ? optional($user)->active : 0);
                        @endphp
                        <input type="checkbox" id="active" name="active" class="form-check-input"
                            {{ isset($show) && $show ? 'disabled' : '' }} {{ $active ? 'checked' : '' }}>
                        <label for="active">{{ __('user.active') }}</label>
                    </div>
                </div>
            </li>
            <li class="d-inline-block me-2 mb-1">
                <div class="form-check">
                    <div class="checkbox">
                        @php
                            $is_admin = old('is_admin', isset($user) ? optional($user)->is_admin : 0);
                        @endphp
                        <input type="checkbox" id="is_admin" name="is_admin" class="form-check-input"
                            {{ isset($show) && $show ? 'disabled' : '' }} {{ $is_admin ? 'checked' : '' }}>
                        <label for="is_admin">{{ __('user.is_admin') }}</label>
                    </div>
                </div>
            </li>
        </ul>
    </div>

</div>
