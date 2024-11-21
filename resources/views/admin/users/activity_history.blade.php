@extends('layouts.admin')

@section('meta_tags')
    <title>{{ __('user.activity_history.activity_history') }} | {{ config('app.name') }}</title>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ mix('css/datatables.css') }}">
@endsection

@section('page_title')
    @include('layouts.admin_partials.title_breadcrumbs', [
        'title' => __('user.activity_history.activity_history'),
        'current_page' => __('user.activity_history.activity_history'),
    ])
@endsection


@section('content')
    @include('admin.datatables.search', [
        'datatable_id' => 'activity-history-table',
        'columns' => [
            [
                'value' => 'created_at',
                'title' => __('user.activity_history.created_at.title'),
                'operators' => 'datetime',
            ],
            [
                'value' => 'causer_id',
                'title' => trans_choice('user.users', 1),
                'operators' => 'list',
                'options' => $users,
            ],
            [
                'value' => 'description',
                'title' => __('user.activity_history.description.title'),
                'operators' => 'string',
            ],
            [
                'value' => 'subject_type',
                'title' => __('user.activity_history.subject_type.title'),
                'operators' => 'string',
            ],
            [
                'value' => 'properties',
                'title' => __('user.activity_history.values.title'),
                'operators' => 'string',
            ],
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
        function clear_activity_history() {
            openAlertModal({
                title: '<i class="bi bi-trash-fill fs-5"></i> ' + "{{ __('user.activity_history.clear.title') }}",
                content: "{{ __('user.activity_history.clear.confirm') }}",
                showCloseBtn: true,
                okBtnText: "{{ __('forms.yes') }}",
                closeBtnText: "{{ __('forms.cancel') }}",
                okBtnAction: function() {
                    let successModalOptions = {
                        okBtnAction: function() {
                            location.replace("{{ route('admin.activity-history') }}")
                        }
                    };
                    let errorModalOptions = {
                        title: "{{ __('admin.error') }}",
                        okBtnAction: function() {}
                    };
                    sendRequest('POST', "{{ route('admin.clear-activity-history') }}",
                        "{{ csrf_token() }}", {}, null, false, successModalOptions, errorModalOptions);
                }
            });
        }
    </script>

    {{ $dataTable->scripts() }}

    <script src="{{ mix('js/search.js') }}"></script>
@endsection
