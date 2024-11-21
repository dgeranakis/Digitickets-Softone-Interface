@extends('layouts.admin')

@section('meta_tags')
    <title>{{ __('auth.my_profile') }} | {{ config('app.name') }}</title>
@endsection

@section('page_title')
    @include('layouts.admin_partials.title_breadcrumbs', [
        'title' => __('auth.my_profile'),
        'sub_title' => __('auth.my_profile_subtitle'),
        'current_page' => __('auth.my_profile'),
    ])
@endsection


@section('content')
    <section id="my-profile" class="mt-3">
        <div class="row match-height justify-content-md-center">
            <div class="col-12 col-md-8">

                <div class="card bg-white">
                    <div class="card-header pb-2">
                        <h4 class="card-title">{{ __('auth.my_profile') }}</h4>
                    </div>

                    <div class="card-content">
                        <div class="card-body pt-2">

                            <form class="form form-horizontal" method="POST"
                                action="{{ route('admin.user-profile-information.update') }}">
                                @csrf
                                @method('PUT')

                                <div class="form-body">
                                    <div class="row">

                                        @if (session('status') == 'profile-information-updated')
                                            <div class="col-md-8 offset-md-4">
                                                <div
                                                    class="alert alert-light-success color-success alert-dismissible show fade">
                                                    <i class="bi bi-check-circle"></i>
                                                    {{ __('auth.profile-information-updated') }}
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close"></button>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-md-4">
                                            <label for="name">{{ __('auth.name') }}</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" name="name"
                                                        class="form-control @error('name', 'updateProfileInformation') is-invalid @enderror"
                                                        placeholder="{{ __('auth.name') }}" id="name"
                                                        value="{{ old('name') ?? auth()->user()->name }}" required
                                                        autocomplete="name" autofocus>
                                                    <div class="form-control-icon"><i class="bi bi-person"></i></div>
                                                    @error('name', 'updateProfileInformation')
                                                        <div class="invalid-feedback">
                                                            <i class="bx bx-radio-circle"></i>
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="email">{{ __('auth.email') }}</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="email" name="email"
                                                        class="form-control @error('email', 'updateProfileInformation') is-invalid @enderror"
                                                        placeholder="{{ __('auth.email') }}" id="email"
                                                        value="{{ old('email') ?? auth()->user()->email }}" required
                                                        autocomplete="email">
                                                    <div class="form-control-icon"><i class="bi bi-envelope"></i></div>
                                                    @error('email', 'updateProfileInformation')
                                                        <div class="invalid-feedback">
                                                            <i class="bx bx-radio-circle"></i>
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="lines_per_page">{{ __('user.lines_per_page.title') }}</label>
                                        </div>
                                        <div class="col-md-8">
                                            @php
                                                $lines_per_page_value =
                                                    old('lines_per_page') ?? auth()->user()->lines_per_page;
                                                $options = [10, 25, 50, 75, 100];
                                            @endphp
                                            <select
                                                class="form-select selectPicker @error('lines_per_page', 'updateProfileInformation') is-invalid @enderror"
                                                id="lines_per_page" name="lines_per_page">
                                                <option value="">{{ __('user.lines_per_page.placeholder') }}</option>
                                                @foreach ($options as $option)
                                                    <option value="{{ $option }}"
                                                        @if ($lines_per_page_value == $option) selected @endif>
                                                        {{ $option }}</option>
                                                @endforeach
                                            </select>
                                            @error('lines_per_page', 'updateProfileInformation')
                                                <div class="invalid-feedback">
                                                    <i class="bx bx-radio-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-12 d-flex justify-content-end mt-3">
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
