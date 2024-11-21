@extends('layouts.admin')

@section('meta_tags')
    <title>{{ trans_choice('enumeration.enumerations', 2) }} | {{ config('app.name') }}</title>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ mix('css/datatables.css') }}">
@endsection

@section('page_title')
    @include('layouts.admin_partials.title_breadcrumbs', [
        'title' => trans_choice('enumeration.enumerations', 2),
        'current_page' => trans_choice('enumeration.enumerations', 2),
    ])
@endsection


@section('content')
    @can('filter selection lists')
        @include('admin.datatables.search', [
            'datatable_id' => 'enumerations-table',
            'columns' => [
                ['value' => 'code', 'title' => __('enumeration.code.title'), 'operators' => 'string'],
                [
                    'value' => 'description',
                    'title' => __('enumeration.description.title'),
                    'operators' => 'translation',
                ],
                [
                    'value' => 'domain_id',
                    'title' => __('enumeration.domain.title'),
                    'operators' => 'list',
                    'options' => $domains,
                ],
                ['value' => 'active', 'title' => __('enumeration.active'), 'operators' => 'boolean'],
            ],
        ])
    @endcan

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
        const enumeration_selectors = {
            code: '#code',
            description: '#description',
            domain: '#domain'
        };
        const enumeration_checkbox_selectors = {
            active: '#active'
        };

        function addNewEnumerationLine() {
            document.querySelector('.btn#create-enumerations').style.display = 'none';

            let trHtml = document.createElement('tr');
            trHtml.setAttribute("id", "new-enumeration-line");
            trHtml.innerHTML =
                '<td><input type="text" class="form-control" id="code" placeholder="{{ __('enumeration.code.placeholder') }}"></td>' +
                '<td><select class="form-select domain" id="domain"><option value="">{{ __('enumeration.domain.placeholder') }}</option>'
            @foreach ($domains as $domain)
                +'<option value="{{ $domain['id'] }}" >{{ $domain['value'] }}</option>'
            @endforeach +
            '</select></td>' +
            '<td><input type="text" class="form-control" id="description" placeholder="{{ __('enumeration.description.placeholder') }}"></td>' +
            '<td class="text-center"><input type="checkbox" class="form-check-input" id="active"></td>' +
            newLineCreateButtons("{{ __('forms.save') }}", "{{ __('forms.cancel') }}");

            document.querySelector('#enumerations-table tbody').prepend(trHtml);
            document.querySelector("#enumerations-table tbody #new-enumeration-line .insert-btn").onclick = function() {
                create_enumeration();
            };
            document.querySelector("#enumerations-table tbody #new-enumeration-line .cancel-insert-btn").onclick =
                function() {
                    document.querySelector('.btn#create-enumerations').style.display = 'inline-block';
                    document.querySelector('#new-enumeration-line').remove();
                };

            createSelectPicker(document.querySelector("select#domain"), {
                language: "{{ appLocale() }}",
                search: true,
                clearable: true
            });

            document.querySelector("#new-enumeration-line #code").focus();
        }

        function create_enumeration() {
            clearErrors('#new-enumeration-line');
            let data = getFormData(enumeration_selectors, enumeration_checkbox_selectors);

            let successModalOptions = {
                okBtnAction: function() {
                    window.LaravelDataTables['enumerations-table'].ajax.reload()
                }
            };
            let errorModalOptions = {
                title: "{{ __('admin.error') }}",
                okBtnAction: function() {}
            };
            sendRequest('POST', "{{ route('admin.enumerations.store') }}", "{{ csrf_token() }}", data, null, true,
                successModalOptions, errorModalOptions);
        }

        function update_enumeration(id, url) {
            clearErrors('.input-' + id);
            let data = getFormData(enumeration_selectors, enumeration_checkbox_selectors, null, null, id);

            let successModalOptions = {
                okBtnAction: function() {
                    window.LaravelDataTables['enumerations-table'].ajax.reload()
                }
            };
            let errorModalOptions = {
                title: "{{ __('admin.error') }}",
                okBtnAction: function() {
                    window.LaravelDataTables['enumerations-table'].ajax.reload()
                }
            };
            sendRequest('PUT', url, "{{ csrf_token() }}", data, id, true, successModalOptions, errorModalOptions);
        }

        function delete_enumeration(id, url) {
            openAlertModal({
                title: '<i class="bi bi-trash-fill fs-5"></i> ' + "{{ __('enumeration.delete.title') }}",
                content: "{{ __('enumeration.delete.confirm') }}",
                showCloseBtn: true,
                okBtnText: "{{ __('forms.yes') }}",
                closeBtnText: "{{ __('forms.cancel') }}",
                okBtnAction: function() {
                    let successModalOptions = {
                        okBtnAction: function() {
                            window.LaravelDataTables['enumerations-table'].ajax.reload()
                        }
                    };
                    let errorModalOptions = {
                        title: "{{ __('admin.error') }}",
                        okBtnAction: function() {
                            window.LaravelDataTables['enumerations-table'].ajax.reload()
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
