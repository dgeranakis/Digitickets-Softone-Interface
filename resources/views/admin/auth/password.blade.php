@extends('layouts.admin')

@section('meta_tags')
    <title>{{ __('auth.change_password') }} | {{ config('app.name') }}</title>
@endsection

@section('page_title')
    @include('layouts.admin_partials.title_breadcrumbs', [
        'title' => __('auth.change_password'),
        'sub_title' => __('auth.my_profile_subtitle'),
        'current_page' => __('auth.change_password'),
    ])
@endsection

@section('content')
    <section id="change-password" class="mt-3">
        <div class="row match-height justify-content-md-center">
            <div class="col-12 col-md-8">

                <div class="card bg-white">
                    <div class="card-header pb-2">
                        <h4 class="card-title">{{ __('auth.change_password') }}</h4>
                    </div>

                    <div class="card-content">
                        <div class="card-body pt-2">

                            <form class="form form-horizontal" method="POST"
                                action="{{ route('admin.user-password.update') }}">
                                @csrf
                                @method('PUT')

                                <div class="form-body">
                                    <div class="row">

                                        @if (session('status') == 'password-updated')
                                            <div class="col-md-8 offset-md-4">
                                                <div
                                                    class="alert alert-light-success color-success alert-dismissible show fade">
                                                    <i class="bi bi-check-circle"></i> {{ __('auth.password-updated') }}
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close"></button>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-md-4">
                                            <label for="current_password">{{ __('auth.current_password') }}</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="password" name="current_password"
                                                class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                                id="current_password" required autofocus>
                                            @error('current_password', 'updatePassword')
                                                <div class="invalid-feedback">
                                                    <i class="bx bx-radio-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="password">{{ __('auth.password') }}</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="password" name="password"
                                                class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                                id="password" required autocomplete="new-password">
                                            @error('password', 'updatePassword')
                                                <div class="invalid-feedback">
                                                    <i class="bx bx-radio-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label
                                                for="password_confirmation">{{ __('auth.password_confirmation') }}</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="password" name="password_confirmation" class="form-control"
                                                id="password_confirmation" required autocomplete="new-password">
                                        </div>

                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="reset"
                                                class="btn btn-light-secondary me-1 mb-1">{{ __('forms.reset') }}</button>
                                            <button type="submit"
                                                class="btn btn-primary me-1 mb-1">{{ __('forms.submit') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
