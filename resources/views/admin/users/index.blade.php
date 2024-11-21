@extends('layouts.admin')

@section('meta_tags')
    <title>{{ trans_choice('user.users', 2) }} | {{ config('app.name') }}</title>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ mix('css/datatables.css') }}">
@endsection

@section('page_title')
    @include('layouts.admin_partials.title_breadcrumbs', [
        'title' => trans_choice('user.users', 2),
        'current_page' => trans_choice('user.users', 2),
    ])
@endsection


@section('content')
    @include('admin.datatables.search', [
        'datatable_id' => 'users-table',
        'columns' => [
            ['value' => 'name', 'title' => __('user.name.title'), 'operators' => 'string'],
            ['value' => 'email', 'title' => __('user.email.title'), 'operators' => 'string'],
            [
                'value' => 'roles',
                'title' => __('user.roles.title'),
                'operators' => 'list',
                'options' => $roles,
                'multiselect' => 1,
            ],
            ['value' => 'active', 'title' => __('user.active'), 'operators' => 'boolean'],
            ['value' => 'is_admin', 'title' => __('user.is_admin'), 'operators' => 'boolean'],
        ],
    ])

    <section class="section">
        <div class="card bg-white">
            <div class="card-body">
                {{ $dataTable->table(['class' => 'table table-striped table-hover w-100']) }}
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{ mix('js/datatables.js') }}"></script>
    <script src="{{ mix('js/datatables/buttons.server-side.js') }}"></script>

    @include('admin.users.js')

    {{ $dataTable->scripts() }}

    <script src="{{ mix('js/search.js') }}"></script>
@endsection
