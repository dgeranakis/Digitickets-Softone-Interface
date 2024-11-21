<?php

namespace App\DataTables;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PermissionsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('name', function (Permission $permission) {
                return view('admin.datatables.input_cell', [
                    'id' => $permission->id,
                    'value' => $permission->name,
                    'input_type' => 'text',
                    'class' => 'name',
                    'html_id' => 'name-' . $permission->id,
                    'placeholder' => __('permission.name.placeholder'),
                ]);
            })
            ->editColumn('action', function (Permission $permission) {
                return view('admin.datatables.action_buttons', [
                    'id' => $permission->id,
                    'route' => 'admin.permissions',
                    'route_parameter' => 'permission',
                    'edit_type' => 'inline',
                    'can_edit' => auth()->user()->hasRole('Super Admin'),
                    'can_delete' => auth()->user()->hasRole('Super Admin'),
                    'edit_label' => __('permission.edit.title'),
                    'delete_label' => __('permission.delete.title'),
                    'datatable_id' => 'permissions-table',
                    'focus_input' => 'name',
                    'js_function_identifier' => 'permission',
                ]);
            })
            ->addColumn('exportName', function (Permission $permission) {
                return $permission->name;
            })
            ->filter(function ($query) {
                if (request()->has('search') && isset(request()->search['value']) && !empty(request()->search['value'])) {
                    $search_term = request()->search['value'];
                    $query->where('permissions.name', 'like', "%" . $search_term . "%");
                }

                if (is_array($this->adv_search)) {
                    createQuery('permissions', $this->adv_search, $query);
                }
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Permission $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('permissions-table')
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
                    document.querySelector(".btn#create-permissions").style.display = "inline-block";
                    if(document.querySelector("#new-permission-line")) document.querySelector("#new-permission-line").remove();
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
            Column::make('name')
                ->title(__('permission.name.title'))
                ->width('90%')
                ->exportable(false)
                ->printable(false),
            Column::make('exportName')
                ->title(__('permission.name.title'))
                ->exportable(true)
                ->printable(true)
                ->orderable(false)
                ->searchable(false)
                ->addClass('d-none'),
        ];

        if (auth()->user()->canany(['create permissions', 'edit permissions', 'delete permissions'])) {

            $title = '';
            if (auth()->user()->can('create permissions')) {
                $title = dataTableCreateBtn(__('permission.create.title'), 'create-permissions', 'addNewPermissionLine()');
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
        return trans_choice('permission.permissions', 2) . '_' . date('Y-m-d.His');
    }
}
