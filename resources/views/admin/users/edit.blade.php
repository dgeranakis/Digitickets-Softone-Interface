@extends('layouts.admin')

@section('meta_tags')
    <title>{{ trans_choice('user.users', 2) }} | {{ config('app.name') }}</title>
@endsection

@section('page_title')
    @include('layouts.admin_partials.title_breadcrumbs', [
        'title' => __('user.edit.title') . ': ' . $user->name,
        'current_page' => __('user.edit.title'),
        'paths' => [['url' => route('admin.users.index'), 'title' => trans_choice('user.users', 2)]],
    ])
@endsection

@section('content')
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card bg-white">
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" id="edit_user" method="POST">

                                @include('admin.users.form')

                                <div class="row d-print-none">
                                    <div class="col-12 d-sm-flex justify-content-end">
                                        <button type="button" class="btn btn-outline-danger me-1 mb-1"
                                            onclick="delete_user({{ $user->id }}, '{{ route('admin.users.destroy', ['user' => $user->id]) }}')"><i
                                                class="bi bi-trash-fill"></i> {{ __('forms.delete') }}</button>
                                        <button type="button" class="btn btn-light-secondary me-1 mb-1"
                                            onclick="clearErrors('#edit_user');document.getElementById('edit_user').reset();reset_selectPickers('edit_user');"><i
                                                class="bi bi-arrow-clockwise"></i> {{ __('forms.reset') }}</button>
                                        <button type="button" class="btn btn-primary me-1 mb-1"
                                            onclick="update_user({{ $user->id }}, '{{ route('admin.users.update', ['user' => $user->id]) }}')"><i
                                                class="bi bi-save"></i> {{ __('forms.submit') }}</button>
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

@section('scripts')
    @include('admin.users.js')
@endsection
