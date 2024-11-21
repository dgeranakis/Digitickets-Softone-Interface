<?php

namespace App\DataTables;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RolesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('name', function (Role $role) {
                return view('admin.datatables.input_cell', [
                    'id' => $role->id,
                    'value' => $role->name,
                    'input_type' => 'text',
                    'class' => 'name',
                    'html_id' => 'name-' . $role->id,
                    'placeholder' => __('role.name.placeholder'),
                ]);
            })
            ->addColumn('permissions', function (Role $role) {
                return view('admin.datatables.input_cell', [
                    'id' => $role->id,
                    'value' => $role->permissions->pluck('name')->toArray(),
                    'input_type' => 'multiselect',
                    'class' => 'permissions',
                    'html_id' => 'permissions-' . $role->id,
                    'placeholder' => __('role.permissions.placeholder'),
                    'options' => $this->permissions
                ]);
            })
            ->editColumn('action', function (Role $role) {
                return view('admin.datatables.action_buttons', [
                    'id' => $role->id,
                    'route' => 'admin.roles',
                    'route_parameter' => 'role',
                    'edit_type' => 'inline',
                    'can_edit' => auth()->user()->hasRole('Super Admin'),
                    'can_delete' => auth()->user()->hasRole('Super Admin'),
                    'edit_label' => __('role.edit.title'),
                    'delete_label' => __('role.delete.title'),
                    'datatable_id' => 'roles-table',
                    'focus_input' => 'name',
                    'js_function_identifier' => 'role',
                ]);
            })
            ->addColumn('exportName', function (Role $role) {
                return $role->name;
            })
            ->addColumn('exportPermissions', function (Role $role) {
                return implode(', ', $role->permissions->pluck('name')->toArray());
            })
            ->filter(function ($query) {
                if (request()->has('search') && isset(request()->search['value']) && !empty(request()->search['value'])) {
                    $search_term = request()->search['value'];
                    $query->where('roles.name', 'like', "%" . $search_term . "%");
                }

                if (is_array($this->search_permissions)) {
                    foreach ($this->search_permissions as $where) {
                        switch ($where->operator) {
                            case "is":
                                $query->whereHas('permissions', function ($q) use ($where) {
                                    $permissions = explode(',', $where->value);
                                    foreach ($permissions as $key => $permission) {
                                        if ($key == 0) $q->where('name', $permission);
                                        else $q->orWhere('name', $permission);
                                    }
                                });
                                break;
                            case "isnot":
                                $query->where(function ($qr) use ($where) {
                                    $qr->doesntHave('permissions')
                                        ->orWhereHas('permissions', function ($q) use ($where) {
                                            $permissions = explode(',', $where->value);
                                            foreach ($permissions as $key => $permission) {
                                                $q->where('name', '!=', $permission);
                                            }
                                        });
                                });
                                break;
                            case "none":
                                $query->doesntHave('permissions');
                                break;
                            case "any":
                                $query->has('permissions');
                                break;
                            default:
                                break;
                        }
                    }
                }

                if (is_array($this->adv_search)) {
                    createQuery('roles', $this->adv_search, $query);
                }
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Role $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('roles-table')
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
                    document.querySelector(".btn#create-roles").style.display = "inline-block";
                    if(document.querySelector("#new-role-line")) document.querySelector("#new-role-line").remove();
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
                ->title(__('role.name.title'))
                ->width('45%')
                ->exportable(false)
                ->printable(false),
            Column::make('exportName')
                ->title(__('role.name.title'))
                ->exportable(true)
                ->printable(true)
                ->orderable(false)
                ->searchable(false)
                ->addClass('d-none'),
            Column::make('permissions')
                ->title(__('role.permissions.title'))
                ->width('45%')
                ->exportable(false)
                ->printable(false)
                ->orderable(false),
            Column::make('exportPermissions')
                ->title(__('role.permissions.title'))
                ->exportable(true)
                ->printable(true)
                ->orderable(false)
                ->searchable(false)
                ->addClass('d-none'),
        ];

        if (auth()->user()->canany(['create roles', 'edit roles', 'delete roles'])) {

            $title = '';
            if (auth()->user()->can('create roles')) {
                $title = dataTableCreateBtn(__('role.create.title'), 'create-roles', 'addNewRoleLine()');
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
        return trans_choice('role.roles', 2) . '_' . date('Y-m-d.His');
    }
}
