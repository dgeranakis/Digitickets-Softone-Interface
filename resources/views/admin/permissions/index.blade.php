@extends('layouts.admin')

@section('meta_tags')
    <title>{{ trans_choice('permission.permissions', 2) }} | {{ config('app.name') }}</title>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ mix('css/datatables.css') }}">
@endsection

@section('page_title')
    @include('layouts.admin_partials.title_breadcrumbs', [
        'title' => trans_choice('permission.permissions', 2),
        'current_page' => trans_choice('permission.permissions', 2),
    ])
@endsection


@section('content')
    @include('admin.datatables.search', [
        'datatable_id' => 'permissions-table',
        'columns' => [['value' => 'name', 'title' => __('permission.name.title'), 'operators' => 'string']],
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
        const permission_selectors = {
            name: '#name'
        };

        function addNewPermissionLine() {
            document.querySelector('.btn#create-permissions').style.display = 'none';

            let trHtml = document.createElement('tr');
            trHtml.setAttribute("id", "new-permission-line");
            trHtml.innerHTML =
                '<td><input type="text" class="form-control" id="name" placeholder="{{ __('permission.name.placeholder') }}"></td>' +
                newLineCreateButtons("{{ __('forms.save') }}", "{{ __('forms.cancel') }}");

            document.querySelector('#permissions-table tbody').prepend(trHtml);
            document.querySelector("#permissions-table tbody #new-permission-line .insert-btn").onclick = function() {
                create_permission();
            };
            document.querySelector("#permissions-table tbody #new-permission-line .cancel-insert-btn").onclick =
                function() {
                    document.querySelector('.btn#create-permissions').style.display = 'inline-block';
                    document.querySelector('#new-permission-line').remove();
                };

            document.querySelector("#new-permission-line #name").focus();
        }

        function create_permission() {
            clearErrors('#new-permission-line');
            let data = getFormData(permission_selectors);

            let successModalOptions = {
                okBtnAction: function() {
                    window.LaravelDataTables['permissions-table'].ajax.reload()
                }
            };
            let errorModalOptions = {
                title: "{{ __('admin.error') }}",
                okBtnAction: function() {}
            };
            sendRequest('POST', "{{ route('admin.permissions.store') }}", "{{ csrf_token() }}", data, null, true,
                successModalOptions, errorModalOptions);
        }

        function update_permission(id, url) {
            clearErrors('.input-' + id);
            let data = getFormData(permission_selectors, null, null, null, id);

            let successModalOptions = {
                okBtnAction: function() {
                    window.LaravelDataTables['permissions-table'].ajax.reload()
                }
            };
            let errorModalOptions = {
                title: "{{ __('admin.error') }}",
                okBtnAction: function() {
                    window.LaravelDataTables['permissions-table'].ajax.reload()
                }
            };
            sendRequest('PUT', url, "{{ csrf_token() }}", data, id, true, successModalOptions, errorModalOptions);
        }

        function delete_permission(id, url) {
            openAlertModal({
                title: '<i class="bi bi-trash-fill fs-5"></i> ' + "{{ __('permission.delete.title') }}",
                content: "{{ __('permission.delete.confirm') }}",
                showCloseBtn: true,
                okBtnText: "{{ __('forms.yes') }}",
                closeBtnText: "{{ __('forms.cancel') }}",
                okBtnAction: function() {
                    let successModalOptions = {
                        okBtnAction: function() {
                            window.LaravelDataTables['permissions-table'].ajax.reload()
                        }
                    };
                    let errorModalOptions = {
                        title: "{{ __('admin.error') }}",
                        okBtnAction: function() {
                            window.LaravelDataTables['permissions-table'].ajax.reload()
                        }
                    };
                    sendRequest('DELETE', url, "{{ csrf_token() }}", {}, id, true, successModalOptions,
                        errorModalOptions);
                }
            });
        }
    </script>

    {{ $dataTable->scripts() }}

    <script src="{{ mix('js/search.js') }}"></script>
@endsection
