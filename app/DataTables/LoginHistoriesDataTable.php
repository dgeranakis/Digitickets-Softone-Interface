<?php

namespace App\DataTables;

use App\Models\LoginHistory;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class LoginHistoriesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('username', function (LoginHistory $history) {
                return (filled(optional($history->user)->name) ? '<a href="' . route('admin.users.edit', ['user' => $history->user->id]) . '" target="_blank">' . $history->user->name . '</a>' : '');
            })
            ->addColumn('exportUsername', function (LoginHistory $history) {
                return optional($history->user)->name;
            })
            ->editColumn('device_type', function (LoginHistory $history) {
                return ucfirst($history->device_type);
            })
            ->editColumn('signin', function (LoginHistory $history) {
                return (filled($history->signin) ? changeDateTimeFormat($history->signin) : '');
            })
            ->editColumn('signout', function (LoginHistory $history) {
                return (filled($history->signout) ? changeDateTimeFormat($history->signout) : '');
            })
            ->rawColumns(['username'])
            ->order(function ($query) {

                if (request()->has('order')) {
                    foreach (request()->order as $order) {
                        $column_num = $order['column'];
                        $dir = $order['dir'];
                        $column = request()->columns[$column_num]['data'];

                        if ($column == 'username') {
                            $query->leftJoin('users', 'users.id', '=', 'login_histories.user_id')
                                ->orderBy('users.name', $dir)
                                ->addSelect('login_histories.*');
                        } else $query->orderBy($column, $dir);
                    }
                }
            })
            ->filter(function ($query) {
                if (request()->has('search') && isset(request()->search['value']) && !empty(request()->search['value'])) {
                    $search_term = request()->search['value'];

                    $query->where(function ($qr) use ($search_term) {
                        $qr->where('login_histories.ip_address', 'like', "%" . $search_term . "%")
                            ->orWhere('login_histories.operating_system', 'like', "%" . $search_term . "%")
                            ->orWhere('login_histories.device_type', 'like', "%" . $search_term . "%")
                            ->orWhere('login_histories.browser', 'like', "%" . $search_term . "%")
                            ->orWhere('login_histories.signin', 'like', "%" . $search_term . "%")
                            ->orWhere('login_histories.signout', 'like', "%" . $search_term . "%")
                            ->orWhereHas('user', function ($q) use ($search_term) {
                                $q->where('name', 'like', "%" . $search_term . "%");
                            });
                    });
                }

                if (is_array($this->adv_search)) {
                    createQuery('login_histories', $this->adv_search, $query);
                }
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(LoginHistory $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('login-history-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('<"d-print-none" B><"d-flex justify-content-between my-3 d-print-none" <l><f>><"table-responsive" t><"d-flex justify-content-between my-3 d-print-none" <i><p>><"clear">')  // Blfrtip
            ->orderBy(6, 'desc')
            ->buttons([
                Button::make('excel')->text('<i class="bi bi-file-earmark-excel"></i> Excel'),
                Button::make('csv')->text('<i class="bi bi-filetype-csv"></i> CSV'),
                Button::make('pdf')->text('<i class="bi bi-file-earmark-pdf"></i> PDF'),
                Button::make('print')->text('<i class="bi bi-printer"></i> ' . __('admin.print')),
                Button::make('reset')->text('<i class="bi bi-trash-fill"></i> ' . __('user.login_history.clear.title'))->className('btn-danger')
                    ->action("function(e, dt, node, config){ clear_login_history(); }"),
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
        $columns = [
            Column::make('username')
                ->title(trans_choice('user.users', 1))
                ->width('12%')
                ->exportable(false)
                ->printable(false),
            Column::make('exportUsername')
                ->title(trans_choice('user.users', 1))
                ->exportable(true)
                ->printable(true)
                ->orderable(false)
                ->searchable(false)
                ->addClass('d-none'),
            Column::make('ip_address')->title(__('user.login_history.ip_address.title'))->width('12%'),
            Column::make('operating_system')->title(__('user.login_history.operating_system.title'))->width('12%'),
            Column::make('browser')->title(__('user.login_history.browser.title'))->width('12%'),
            Column::make('device_type')->title(__('user.login_history.device_type.title'))->width('12%'),
            Column::make('signin')->title(__('user.login_history.signin.title'))->width('20%'),
            Column::make('signout')->title(__('user.login_history.signout.title'))->width('20%'),
        ];

        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return __('user.login_history.login_history') . '_' . date('Y-m-d.His');
    }
}
