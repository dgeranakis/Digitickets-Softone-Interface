@extends('layouts.admin')

@section('meta_tags')
    <title>{{ __('user.login_history.login_history') }} | {{ config('app.name') }}</title>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ mix('css/datatables.css') }}">
@endsection

@section('page_title')
    @include('layouts.admin_partials.title_breadcrumbs', [
        'title' => __('user.login_history.login_history'),
        'current_page' => __('user.login_history.login_history'),
    ])
@endsection


@section('content')
    @include('admin.datatables.search', [
        'datatable_id' => 'login-history-table',
        'columns' => [
            [
                'value' => 'user_id',
                'title' => trans_choice('user.users', 1),
                'operators' => 'list',
                'options' => $users,
            ],
            [
                'value' => 'ip_address',
                'title' => __('user.login_history.ip_address.title'),
                'operators' => 'string',
            ],
            [
                'value' => 'operating_system',
                'title' => __('user.login_history.operating_system.title'),
                'operators' => 'string',
            ],
            ['value' => 'browser', 'title' => __('user.login_history.browser.title'), 'operators' => 'string'],
            [
                'value' => 'device_type',
                'title' => __('user.login_history.device_type.title'),
                'operators' => 'list',
                'options' => $device_types,
            ],
            ['value' => 'signin', 'title' => __('user.login_history.signin.title'), 'operators' => 'datetime'],
            ['value' => 'signout', 'title' => __('user.login_history.signout.title'), 'operators' => 'datetime'],
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

    <script>
        function clear_login_history() {
            openAlertModal({
                title: '<i class="bi bi-trash-fill fs-5"></i> ' + "{{ __('user.login_history.clear.title') }}",
                content: "{{ __('user.login_history.clear.confirm') }}",
                showCloseBtn: true,
                okBtnText: "{{ __('forms.yes') }}",
                closeBtnText: "{{ __('forms.cancel') }}",
                okBtnAction: function() {
                    let successModalOptions = {
                        okBtnAction: function() {
                            location.replace("{{ route('admin.login-history') }}")
                        }
                    };
                    let errorModalOptions = {
                        title: "{{ __('admin.error') }}",
                        okBtnAction: function() {}
                    };
                    sendRequest('POST', "{{ route('admin.clear-login-history') }}", "{{ csrf_token() }}", {},
                        null, false, successModalOptions, errorModalOptions);
                }
            });
        }
    </script>

    {{ $dataTable->scripts() }}

    <script src="{{ mix('js/search.js') }}"></script>
@endsection
