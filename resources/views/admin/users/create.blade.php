@extends('layouts.admin')

@section('meta_tags')
    <title>{{ trans_choice('user.users', 2) }} | {{ config('app.name') }}</title>
@endsection

@section('page_title')
    @include('layouts.admin_partials.title_breadcrumbs', [
        'title' => __('user.create.title'),
        'current_page' => __('user.create.title'),
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
                            <form class="form" id="create_user" method="POST">

                                @include('admin.users.form')

                                <div class="row d-print-none">
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="button" class="btn btn-light-secondary me-1 mb-1"
                                            onclick="clearErrors('#create_user');document.getElementById('create_user').reset();reset_selectPickers('create_user');"><i
                                                class="bi bi-arrow-clockwise"></i> {{ __('forms.reset') }}</button>
                                        <button type="button" class="btn btn-primary me-1 mb-1" onclick="create_user()"><i
                                                class="bi bi-save"></i> {{ __('forms.create') }}</button>
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
