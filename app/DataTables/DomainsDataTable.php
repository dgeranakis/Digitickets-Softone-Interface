<?php

namespace App\DataTables;

use App\Models\Domain;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DomainsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('code', function (Domain $domain) {
                return view('admin.datatables.input_cell', [
                    'id' => $domain->id,
                    'value' => $domain->code,
                    'input_type' => 'text',
                    'class' => 'code',
                    'html_id' => 'code-' . $domain->id,
                    'placeholder' => __('domain.code.placeholder'),
                ]);
            })
            ->editColumn('description', function (Domain $domain) {
                return view('admin.datatables.input_cell', [
                    'id' => $domain->id,
                    'value' => $domain->description,
                    'input_type' => 'text',
                    'class' => 'description',
                    'html_id' => 'description-' . $domain->id,
                    'placeholder' => __('domain.description.placeholder'),
                ]);
            })
            ->editColumn('action', function (Domain $domain) {
                return view('admin.datatables.action_buttons', [
                    'id' => $domain->id,
                    'route' => 'admin.domains',
                    'route_parameter' => 'domain',
                    'edit_type' => 'inline',
                    'can_edit' => auth()->user()->hasRole('Super Admin'),
                    'can_delete' => auth()->user()->hasRole('Super Admin'),
                    'edit_label' => __('domain.edit.title'),
                    'delete_label' => __('domain.delete.title'),
                    'datatable_id' => 'domains-table',
                    'focus_input' => 'code',
                    'js_function_identifier' => 'domain',
                ]);
            })
            ->addColumn('exportCode', function (Domain $domain) {
                return $domain->code;
            })
            ->addColumn('exportDescription', function (Domain $domain) {
                return $domain->description;
            })
            ->order(function ($query) {

                if (request()->has('order')) {
                    foreach (request()->order as $order) {
                        $column_num = $order['column'];
                        $dir = $order['dir'];
                        $column = request()->columns[$column_num]['data'];

                        if ($column == 'description') $query->orderByTranslation('description', $dir);
                        else $query->orderBy($column, $dir);
                    }
                }
            })
            ->filter(function ($query) {
                if (request()->has('search') && isset(request()->search['value']) && !empty(request()->search['value'])) {
                    $search_term = request()->search['value'];

                    $query->where(function ($qr) use ($search_term) {
                        $qr->whereTranslationLike('description', "%" . $search_term . "%")
                            ->orWhere('domains.code', 'like', "%" . $search_term . "%");
                    });
                }

                if (is_array($this->adv_search)) {
                    createQuery('domains', $this->adv_search, $query);
                }
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Domain $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('domains-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('<"d-print-none" B><"d-flex justify-content-between my-3 d-print-none" <l><f>><"table-responsive" t><"d-flex justify-content-between my-3 d-print-none" <i><p>><"clear">')  // Blfrtip
            ->orderBy(0, 'asc')
            ->buttons([
                Button::make('excel')->text('<i class="bi bi-file-earmark-excel"></i> Excel'),
                Button::make('csv')->text('<i class="bi bi-filetype-csv"></i> CSV'),
                Button::make('pdf')->text('<i class="bi bi-file-earmark-pdf"></i> PDF'),
                Button::make('print')->text('<i class="bi bi-printer"></i> ' . __('admin.print')),
            ])
            ->ajax([
                'data' => 'function(d){d.adv_search = document.querySelector("#accordionSearch #adv-search").value;}'
            ])
            ->parameters([
                'paging' => true,
                'searching' => true,
                'info' => true,
                'lengthMenu' => [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, __('admin.all')]],
                'pageLength' => userLinesPerPage(),
                'pagingType' => 'full_numbers',
                'drawCallback' => 'function() {
                    document.querySelector(".btn#create-domains").style.display = "inline-block";
                    if(document.querySelector("#new-domain-line")) document.querySelector("#new-domain-line").remove();
                }',
                'language' => ['url' => asset('js/datatables/datatables.' . appLocale() . '.json')],
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $columns = [
            Column::make('code')
                ->title(__('domain.code.title'))
                ->width('45%')
                ->exportable(false)
                ->printable(false),
            Column::make('exportCode')
                ->title(__('domain.code.title'))
                ->exportable(true)
                ->printable(true)
                ->orderable(false)
                ->searchable(false)
                ->addClass('d-none'),
            Column::make('description')
                ->title(__('domain.description.title'))
                ->width('45%')
                ->exportable(false)
                ->printable(false),
            Column::make('exportDescription')
                ->title(__('domain.description.title'))
                ->exportable(true)
                ->printable(true)
                ->orderable(false)
                ->searchable(false)
                ->addClass('d-none'),
        ];

        if (auth()->user()->canany(['create domains', 'edit domains', 'delete domains'])) {

            $title = '';
            if (auth()->user()->can('create domains')) {
                $title = dataTableCreateBtn(__('domain.create.title'), 'create-domains', 'addNewDomainLine()');
            }

            $columns[] = Column::computed('action')
                ->title($title)
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->width('10%')
                ->addClass('text-center text-nowrap d-print-none');
        }

        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return trans_choice('domain.domains', 2) . '_' . date('Y-m-d.His');
    }
}
