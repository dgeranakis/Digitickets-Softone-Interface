<?php

namespace App\DataTables;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ActivityHistoriesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('description', function (Activity $activity) {
                return $activity->description;
            })
            ->editColumn('subject_type', function (Activity $activity) {
                return class_basename($activity->subject_type);
            })
            ->editColumn('created_at', function (Activity $activity) {
                return (filled($activity->created_at) ? changeDateTimeFormat($activity->created_at) : '');
            })
            ->addColumn('user', function (Activity $activity) {
                if (class_basename($activity->causer_type) == 'User') {
                    return (filled(optional($activity->causer)->name) ? '<a href="' . route('admin.users.edit', ['user' => $activity->causer->id]) . '" target="_blank">' . $activity->causer->name . '</a>' : '');
                }
                return '';
            })
            ->addColumn('exportUser', function (Activity $activity) {
                return (class_basename($activity->causer_type) == 'User' && filled(optional($activity->causer)->name) ? $activity->causer->name : '');
            })
            ->addColumn('values', function (Activity $activity) {
                $values = [];
                $properties = json_decode($activity->properties);
                if (isset($properties->attributes)) {
                    foreach ($properties->attributes as $key => $value) {
                        $values[] = '"' . $key . '": "' . $value . '"';
                    }
                }
                return implode(', ', $values);
            })
            ->addColumn('old_values', function (Activity $activity) {
                $values = [];
                $properties = json_decode($activity->properties);
                if (isset($properties->old)) {
                    foreach ($properties->old as $key => $value) {
                        $values[] = '"' . $key . '": "' . $value . '"';
                    }
                }
                return implode(', ', $values);
            })
            ->rawColumns(['user', 'values', 'old_values'])
            ->order(function ($query) {

                if (request()->has('order')) {
                    foreach (request()->order as $order) {
                        $column_num = $order['column'];
                        $dir = $order['dir'];
                        $column = request()->columns[$column_num]['data'];

                        if ($column == 'user') {
                            $query->leftJoin('users', 'users.id', '=', 'activity_log.causer_id')
                                ->orderBy('users.username', $dir)
                                ->addSelect('activity_log.*');
                        } else {
                            $query->orderBy($column, $dir);
                        }
                    }
                }
            })
            ->filter(function ($query) {
                if (request()->has('search') && isset(request()->search['value']) && !empty(request()->search['value'])) {
                    $search_term = request()->search['value'];

                    $query->where(function ($qr) use ($search_term) {
                        $qr->where('activity_log.description', 'like', "%" . $search_term . "%")
                            ->orWhere('activity_log.subject_type', 'like', "%" . $search_term . "%")
                            ->orWhere('activity_log.created_at', 'like', "%" . $search_term . "%")
                            ->orWhereRaw("activity_log.properties like '%" . trim(str_replace('\\', "\\\\\\\\", json_encode($search_term)), '"') . "%'");
                    });
                }

                if (is_array($this->search_properties)) {
                    foreach ($this->search_properties as $where) {
                        switch ($where->operator) {
                            case "is":
                                $query->whereRaw("activity_log.properties = '" . trim(str_replace('\\', "\\\\\\\\", json_encode($where->value)), '"') . "'");
                                break;
                            case "contains":
                                $query->whereRaw("activity_log.properties like '%" . trim(str_replace('\\', "\\\\\\\\", json_encode($where->value)), '"') . "%'");
                                break;
                            case "none":
                                $query->whereNull('activity_log.properties');
                                break;
                            case "any":
                                $query->whereNotNull('activity_log.properties');
                                break;
                            default:
                                break;
                        }
                    }
                }

                if (is_array($this->adv_search)) {
                    createQuery('activity_log', $this->adv_search, $query);
                }
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Activity $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('activity-history-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('<"d-print-none" B><"d-flex justify-content-between my-3 d-print-none" <l><f>><"table-responsive" t><"d-flex justify-content-between my-3 d-print-none" <i><p>><"clear">')  // Blfrtip
            ->orderBy(0, 'desc')
            ->buttons([
                Button::make('excel')->text('<i class="bi bi-file-earmark-excel"></i> Excel'),
                Button::make('csv')->text('<i class="bi bi-filetype-csv"></i> CSV'),
                Button::make('pdf')->text('<i class="bi bi-file-earmark-pdf"></i> PDF'),
                Button::make('print')->text('<i class="bi bi-printer"></i> ' . __('admin.print')),
                Button::make('reset')->text('<i class="bi bi-trash-fill"></i> ' . __('user.activity_history.clear.title'))->className('btn-danger')
                    ->action("function(e, dt, node, config){ clear_activity_history(); }"),
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
                'language' => ['url' => asset('js/datatables/datatables.' . appLocale() . '.json')],
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('created_at')->title(__('user.activity_history.created_at.title'))->width('15%'),
            Column::make('user')
                ->title(trans_choice('user.users', 1))
                ->width('10%')
                ->exportable(false)
                ->printable(false),
            Column::make('exportUser')
                ->title(trans_choice('user.users', 1))
                ->exportable(true)
                ->printable(true)
                ->orderable(false)
                ->searchable(false)
                ->addClass('d-none'),
            Column::make('description')->title(__('user.activity_history.description.title'))->width('15%'),
            Column::make('subject_type')->title(__('user.activity_history.subject_type.title'))->width('10%'),
            Column::make('values')
                ->title(__('user.activity_history.values.title'))
                ->width('25%')
                ->orderable(false)
                ->searchable(false),
            Column::make('old_values')
                ->title(__('user.activity_history.old_values.title'))
                ->width('25%')
                ->orderable(false)
                ->searchable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return __('user.activity_history.activity_history') . '_' . date('Y-m-d.His');
    }
}
