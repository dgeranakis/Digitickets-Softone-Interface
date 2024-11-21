@extends('layouts.admin')

@section('meta_tags')
    <title>{{ trans_choice('role.roles', 2) }} | {{ config('app.name') }}</title>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ mix('css/datatables.css') }}">
@endsection

@section('page_title')
    @include('layouts.admin_partials.title_breadcrumbs', [
        'title' => trans_choice('role.roles', 2),
        'current_page' => trans_choice('role.roles', 2),
    ])
@endsection


@section('content')
    @include('admin.datatables.search', [
        'datatable_id' => 'roles-table',
        'columns' => [
            ['value' => 'name', 'title' => __('role.name.title'), 'operators' => 'string'],
            [
                'value' => 'permissions',
                'title' => __('role.permissions.title'),
                'operators' => 'list',
                'options' => $permissions,
                'multiselect' => 1,
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
        const role_selectors = {
            name: '#name'
        };
        const role_multiselectors = {
            permissions: '#permissions'
        };

        function addNewRoleLine() {
            document.querySelector('.btn#create-roles').style.display = 'none';

            let trHtml = document.createElement('tr');
            trHtml.setAttribute("id", "new-role-line");
            trHtml.innerHTML =
                '<td><input type="text" class="form-control" id="name" placeholder="{{ __('role.name.placeholder') }}"></td>' +
                '<td><select class="form-select permissions" id="permissions" multiple><option value="">{{ __('role.permissions.placeholder') }}</option>'
            @foreach ($permissions as $permission)
                +'<option value="{{ $permission['value'] }}" >{{ $permission['value'] }}</option>'
            @endforeach +
            '</select></td>' +
            newLineCreateButtons("{{ __('forms.save') }}", "{{ __('forms.cancel') }}");

            document.querySelector('#roles-table tbody').prepend(trHtml);
            document.querySelector("#roles-table tbody #new-role-line .insert-btn").onclick = function() {
                create_role();
            };
            document.querySelector("#roles-table tbody #new-role-line .cancel-insert-btn").onclick = function() {
                document.querySelector('.btn#create-roles').style.display = 'inline-block';
                document.querySelector('#new-role-line').remove();
            };

            createSelectPicker(document.querySelector("select#permissions"), {
                language: "{{ appLocale() }}",
                search: true,
                clearable: true,
                actions: true
            });

            document.querySelector("#new-role-line #name").focus();
        }

        function create_role() {
            clearErrors('#new-role-line');
            let data = getFormData(role_selectors, null, role_multiselectors);

            let successModalOptions = {
                okBtnAction: function() {
                    window.LaravelDataTables['roles-table'].ajax.reload()
                }
            };
            let errorModalOptions = {
                title: "{{ __('admin.error') }}",
                okBtnAction: function() {}
            };
            sendRequest('POST', "{{ route('admin.roles.store') }}", "{{ csrf_token() }}", data, null, true,
                successModalOptions, errorModalOptions);
        }

        function update_role(id, url) {
            clearErrors('.input-' + id);
            let data = getFormData(role_selectors, null, role_multiselectors, null, id);

            let successModalOptions = {
                okBtnAction: function() {
                    window.LaravelDataTables['roles-table'].ajax.reload()
                }
            };
            let errorModalOptions = {
                title: "{{ __('admin.error') }}",
                okBtnAction: function() {
                    window.LaravelDataTables['roles-table'].ajax.reload()
                }
            };
            sendRequest('PUT', url, "{{ csrf_token() }}", data, id, true, successModalOptions, errorModalOptions);
        }

        function delete_role(id, url) {
            openAlertModal({
                title: '<i class="bi bi-trash-fill fs-5"></i> ' + "{{ __('role.delete.title') }}",
                content: "{{ __('role.delete.confirm') }}",
                showCloseBtn: true,
                okBtnText: "{{ __('forms.yes') }}",
                closeBtnText: "{{ __('forms.cancel') }}",
                okBtnAction: function() {
                    let successModalOptions = {
                        okBtnAction: function() {
                            window.LaravelDataTables['roles-table'].ajax.reload()
                        }
                    };
                    let errorModalOptions = {
                        title: "{{ __('admin.error') }}",
                        okBtnAction: function() {
                            window.LaravelDataTables['roles-table'].ajax.reload()
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
