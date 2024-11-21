<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('email', function (User $user) {
                return (filled($user->email) ? '<a href="mailto:' . $user->email . '">' . $user->email . '</a>' : '');
            })
            ->editColumn('active', function (User $user) {
                return ($user->active ? '<i class="bi bi-check-circle text-success"></i>' : '');
            })
            ->editColumn('is_admin', function (User $user) {
                return ($user->is_admin ? '<i class="bi bi-check-circle text-success"></i>' : '');
            })
            ->editColumn('roles', function (User $user) {
                $html = '';
                $roles = $user->roles->pluck('name');
                foreach ($roles as $role) {
                    $html .= '<span class="badge bg-primary"><small>' . $role . '</small></span>';
                }
                return $html;
            })
            ->addColumn('exportEmail', function (User $user) {
                return $user->email;
            })
            ->addColumn('exportActive', function (User $user) {
                return $user->active;
            })
            ->addColumn('exportIsAdmin', function (User $user) {
                return $user->is_admin;
            })
            ->addColumn('exportRoles', function (User $user) {
                return implode(', ', $user->roles->pluck('name')->toArray());
            })
            ->editColumn('action', function (User $user) {
                return view('admin.datatables.action_buttons', [
                    'id' => $user->id,
                    'route' => 'admin.users',
                    'route_parameter' => 'user',
                    'edit_type' => 'form',
                    'can_edit' => auth()->user()->hasRole('Super Admin'),
                    'can_delete' => auth()->user()->hasRole('Super Admin'),
                    'edit_label' => __('user.edit.title'),
                    'delete_label' => __('user.delete.title'),
                    'datatable_id' => 'users-table',
                    'focus_input' => 'name',
                    'js_function_identifier' => 'user',
                ]);
            })
            ->rawColumns(['email', 'active', 'roles', 'is_admin'])
            ->filter(function ($query) {
                if (request()->has('search') && isset(request()->search['value']) && !empty(request()->search['value'])) {
                    $search_term = request()->search['value'];

                    $query->where('users.name', "%" . $search_term . "%")
                        ->orWhere('users.email', 'like', "%" . $search_term . "%")
                        ->orWhereHas('roles', function ($q) use ($search_term) {
                            $q->where('name', 'like', "%" . $search_term . "%");
                        });
                }

                if (is_array($this->search_roles)) {
                    foreach ($this->search_roles as $where) {
                        switch ($where->operator) {
                            case "is":
                                $query->whereHas('roles', function ($q) use ($where) {
                                    $roles = explode(',', $where->value);
                                    foreach ($roles as $key => $role) {
                                        if ($key == 0) $q->where('name', $role);
                                        else $q->orWhere('name', $role);
                                    }
                                });
                                break;
                            case "isnot":
                                $query->where(function ($qr) use ($where) {
                                    $qr->doesntHave('roles')
                                        ->orWhereHas('roles', function ($q) use ($where) {
                                            $roles = explode(',', $where->value);
                                            foreach ($roles as $key => $role) {
                                                $q->where('name', '!=', $role);
                                            }
                                        });
                                });
                                break;
                            case "none":
                                $query->doesntHave('roles');
                                break;
                            case "any":
                                $query->has('roles');
                                break;
                            default:
                                break;
                        }
                    }
                }

                if (is_array($this->adv_search)) {
                    createQuery('users', $this->adv_search, $query);
                }
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()->with('roles');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('users-table')
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
                    document.querySelector(".btn#create-users").style.display = "inline-block";
                    if(document.querySelector("#new-user-line")) document.querySelector("#new-user-line").remove();
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
                ->title(__('user.name.title'))
                ->width('20%')
                ->exportable(true)
                ->printable(true),
            Column::make('email')
                ->title(__('user.email.title'))
                ->width('30%')
                ->exportable(false)
                ->printable(false),
            Column::make('exportEmail')
                ->title(__('user.email.title'))
                ->exportable(true)
                ->printable(true)
                ->orderable(false)
                ->searchable(false)
                ->addClass('d-none'),
            Column::make('roles')
                ->title(__('user.roles.title'))
                ->width('20%')
                ->exportable(false)
                ->printable(false)
                ->orderable(false),
            Column::make('exportRoles')
                ->title(__('user.roles.title'))
                ->exportable(true)
                ->printable(true)
                ->orderable(false)
                ->searchable(false)
                ->addClass('d-none'),
            Column::make('is_admin')
                ->title(__('user.is_admin'))
                ->exportable(true)
                ->printable(true)
                ->width('10%')
                ->addClass('text-center text-nowrap'),
            Column::make('exportIsAdmin')
                ->title(__('user.is_admin'))
                ->exportable(true)
                ->printable(true)
                ->orderable(false)
                ->searchable(false)
                ->addClass('d-none'),
            Column::make('active')
                ->title(__('user.active'))
                ->exportable(true)
                ->printable(true)
                ->width('10%')
                ->addClass('text-center text-nowrap'),
            Column::make('exportActive')
                ->title(__('user.active'))
                ->exportable(true)
                ->printable(true)
                ->orderable(false)
                ->searchable(false)
                ->addClass('d-none'),
        ];

        if (auth()->user()->canany(['create users', 'edit users', 'delete users'])) {

            $title = '';
            if (auth()->user()->can('create users')) {
                $title = dataTableCreateBtn(__('user.create.title'), 'create-users', 'location.replace(\'' . route('admin.users.create') . '\')');
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
        return trans_choice('user.users', 2) . '_' . date('Y-m-d.His');
    }
}
