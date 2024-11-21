<?php

namespace App\DataTables;

use App\Models\Enumeration;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class EnumerationsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('code', function (Enumeration $enumeration) {
                return view('admin.datatables.input_cell', [
                    'id' => $enumeration->id,
                    'value' => $enumeration->code,
                    'input_type' => 'text',
                    'class' => 'code',
                    'html_id' => 'code-' . $enumeration->id,
                    'placeholder' => __('enumeration.code.placeholder'),
                ]);
            })
            ->addColumn('exportCode', function (Enumeration $enumeration) {
                return $enumeration->code;
            })
            ->addColumn('domain', function (Enumeration $enumeration) {
                return view('admin.datatables.input_cell', [
                    'id' => $enumeration->id,
                    'value' => array('id' => $enumeration->domain_id, 'value' => optional($enumeration->domain)->description),
                    'input_type' => 'select',
                    'class' => 'domain',
                    'html_id' => 'domain-' . $enumeration->id,
                    'placeholder' => __('enumeration.domain.placeholder'),
                    'options' => $this->domains
                ]);
            })
            ->addColumn('exportDomain', function (Enumeration $enumeration) {
                return optional($enumeration->domain)->description;
            })
            ->editColumn('description', function (Enumeration $enumeration) {
                return view('admin.datatables.input_cell', [
                    'id' => $enumeration->id,
                    'value' => $enumeration->description,
                    'input_type' => 'text',
                    'class' => 'description',
                    'html_id' => 'description-' . $enumeration->id,
                    'placeholder' => __('enumeration.description.placeholder'),
                ]);
            })
            ->addColumn('exportDescription', function (Enumeration $enumeration) {
                return $enumeration->description;
            })
            ->editColumn('active', function (Enumeration $enumeration) {
                return view('admin.datatables.input_cell', [
                    'id' => $enumeration->id,
                    'value' => $enumeration->active,
                    'input_type' => 'checkbox',
                    'class' => 'active',
                    'html_id' => 'active-' . $enumeration->id,
                    'placeholder' => __('enumeration.active'),
                ]);
            })
            ->addColumn('exportActive', function (Enumeration $enumeration) {
                return $enumeration->active;
            })
            ->editColumn('action', function (Enumeration $enumeration) {
                return view('admin.datatables.action_buttons', [
                    'id' => $enumeration->id,
                    'route' => 'admin.enumerations',
                    'route_parameter' => 'enumeration',
                    'edit_type' => 'inline',
                    'can_edit' => auth()->user()->can('edit selection lists'),
                    'can_delete' => auth()->user()->can('delete selection lists'),
                    'edit_label' => __('enumeration.edit.title'),
                    'delete_label' => __('enumeration.delete.title'),
                    'datatable_id' => 'enumerations-table',
                    'focus_input' => 'code',
                    'js_function_identifier' => 'enumeration',
                ]);
            })
            ->order(function ($query) {

                if (request()->has('order')) {
                    foreach (request()->order as $order) {
                        $column_num = $order['column'];
                        $dir = $order['dir'];
                        $column = request()->columns[$column_num]['data'];

                        if ($column == 'description') $query->orderByTranslation('description', $dir);
                        else if ($column == 'domain') {
                            $query->leftJoin('domains', 'domains.id', '=', 'enumerations.domain_id')
                                ->leftJoin('domain_translations', function ($q) {
                                    $q->on('domains.id', '=', 'domain_translations.domain_id')
                                        ->where('domain_translations.locale', '=', appLocale());
                                })
                                ->orderBy('domain_translations.description', $dir)
                                ->addSelect('enumerations.*');
                        } else $query->orderBy($column, $dir);
                    }
                }
            })
            ->filter(function ($query) {
                if (request()->has('search') && isset(request()->search['value']) && !empty(request()->search['value'])) {
                    $search_term = request()->search['value'];

                    $query->where(function ($qr) use ($search_term) {
                        $qr->whereTranslationLike('description', "%" . $search_term . "%")
                            ->orWhere('enumerations.code', 'like', "%" . $search_term . "%")
                            ->orWhereHas('domain', function ($q) use ($search_term) {
                                $q->whereTranslationLike('description', "%" . $search_term . "%");
                            });
                    });
                }

                if (is_array($this->adv_search)) {
                    createQuery('enumerations', $this->adv_search, $query);
                }
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Enumeration $model): QueryBuilder
    {
        return $model->newQuery()->with('domain');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dom = '<"d-flex justify-content-between my-3 d-print-none" <l><f>><"table-responsive" t><"d-flex justify-content-between my-3 d-print-none" <i><p>><"clear">';
        if (auth()->user()->can('export selection lists')) $dom = '<"d-print-none" B><"d-flex justify-content-between my-3 d-print-none" <l><f>><"table-responsive" t><"d-flex justify-content-between my-3 d-print-none" <i><p>><"clear">';

        return $this->builder()
            ->setTableId('enumerations-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($dom)  // Blfrtip
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
                    document.querySelector(".btn#create-enumerations").style.display = "inline-block";
                    if(document.querySelector("#new-enumeration-line")) document.querySelector("#new-enumeration-line").remove();
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
                ->title(__('enumeration.code.title'))
                ->width('20%')
                ->exportable(false)
                ->printable(false),
            Column::make('exportCode')
                ->title(__('enumeration.code.title'))
                ->exportable(true)
                ->printable(true)
                ->orderable(false)
                ->searchable(false)
                ->addClass('d-none'),
            Column::make('domain')
                ->title(__('enumeration.domain.title'))
                ->width('30%')
                ->exportable(false)
                ->printable(false),
            Column::make('exportDomain')
                ->title(__('enumeration.domain.title'))
                ->exportable(true)
                ->printable(true)
                ->orderable(false)
                ->searchable(false)
                ->addClass('d-none'),
            Column::make('description')
                ->title(__('enumeration.description.title'))
                ->width('30%')
                ->exportable(false)
                ->printable(false),
            Column::make('exportDescription')
                ->title(__('enumeration.description.title'))
                ->exportable(true)
                ->printable(true)
                ->orderable(false)
                ->searchable(false)
                ->addClass('d-none'),
            Column::make('active')
                ->title(__('enumeration.active'))
                ->exportable(false)
                ->printable(false)
                ->width('10%')
                ->addClass('text-center'),
            Column::make('exportActive')
                ->title(__('enumeration.active'))
                ->exportable(true)
                ->printable(true)
                ->orderable(false)
                ->searchable(false)
                ->addClass('d-none'),
        ];

        if (auth()->user()->canany(['create selection lists', 'edit selection lists', 'delete selection lists'])) {

            $title = '';
            if (auth()->user()->can('create selection lists')) {
                $title = dataTableCreateBtn(__('enumeration.create.title'), 'create-enumerations', 'addNewEnumerationLine()');
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
        return trans_choice('enumeration.enumerations', 2) . '_' . date('Y-m-d.His');
    }
}
