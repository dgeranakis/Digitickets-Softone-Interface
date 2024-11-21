@extends('layouts.admin')

@section('meta_tags')
    <title>{{ trans_choice('domain.domains', 2) }} | {{ config('app.name') }}</title>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ mix('css/datatables.css') }}">
@endsection

@section('page_title')
    @include('layouts.admin_partials.title_breadcrumbs', [
        'title' => trans_choice('domain.domains', 2),
        'current_page' => trans_choice('domain.domains', 2),
    ])
@endsection


@section('content')
    @can('filter domains')
        @include('admin.datatables.search', [
            'datatable_id' => 'domains-table',
            'columns' => [
                ['value' => 'code', 'title' => __('domain.code.title'), 'operators' => 'string'],
                [
                    'value' => 'description',
                    'title' => __('domain.description.title'),
                    'operators' => 'translation',
                ],
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
        const domain_selectors = {
            code: '#code',
            description: '#description'
        };

        function addNewDomainLine() {
            document.querySelector('.btn#create-domains').style.display = 'none';

            let trHtml = document.createElement('tr');
            trHtml.setAttribute("id", "new-domain-line");
            trHtml.innerHTML =
                '<td><input type="text" class="form-control" id="code" placeholder="{{ __('domain.code.placeholder') }}"></td>' +
                '<td><input type="text" class="form-control" id="description" placeholder="{{ __('domain.description.placeholder') }}"></td>' +
                newLineCreateButtons("{{ __('forms.save') }}", "{{ __('forms.cancel') }}");

            document.querySelector('#domains-table tbody').prepend(trHtml);
            document.querySelector("#domains-table tbody #new-domain-line .insert-btn").onclick = function() {
                create_domain();
            };
            document.querySelector("#domains-table tbody #new-domain-line .cancel-insert-btn").onclick = function() {
                document.querySelector('.btn#create-domains').style.display = 'inline-block';
                document.querySelector('#new-domain-line').remove();
            };

            document.querySelector("#new-domain-line #code").focus();
        }

        function create_domain() {
            clearErrors('#new-domain-line');
            let data = getFormData(domain_selectors);

            let successModalOptions = {
                okBtnAction: function() {
                    window.LaravelDataTables['domains-table'].ajax.reload()
                }
            };
            let errorModalOptions = {
                title: "{{ __('admin.error') }}",
                okBtnAction: function() {}
            };
            sendRequest('POST', "{{ route('admin.domains.store') }}", "{{ csrf_token() }}", data, null, true,
                successModalOptions, errorModalOptions);
        }

        function update_domain(id, url) {
            clearErrors('.input-' + id);
            let data = getFormData(domain_selectors, null, null, null, id);

            let successModalOptions = {
                okBtnAction: function() {
                    window.LaravelDataTables['domains-table'].ajax.reload()
                }
            };
            let errorModalOptions = {
                title: "{{ __('admin.error') }}",
                okBtnAction: function() {
                    window.LaravelDataTables['domains-table'].ajax.reload()
                }
            };
            sendRequest('PUT', url, "{{ csrf_token() }}", data, id, true, successModalOptions, errorModalOptions);
        }

        function delete_domain(id, url) {
            openAlertModal({
                title: '<i class="bi bi-trash-fill fs-5"></i> ' + "{{ __('domain.delete.title') }}",
                content: "{{ __('domain.delete.confirm') }}",
                showCloseBtn: true,
                okBtnText: "{{ __('forms.yes') }}",
                closeBtnText: "{{ __('forms.cancel') }}",
                okBtnAction: function() {
                    let successModalOptions = {
                        okBtnAction: function() {
                            window.LaravelDataTables['domains-table'].ajax.reload()
                        }
                    };
                    let errorModalOptions = {
                        title: "{{ __('admin.error') }}",
                        okBtnAction: function() {
                            window.LaravelDataTables['domains-table'].ajax.reload()
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
