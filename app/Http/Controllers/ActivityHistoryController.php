<?php

namespace App\Http\Controllers;

use Spatie\Activitylog\Models\Activity;
use App\Models\User;
use App\DataTables\ActivityHistoriesDataTable;
use Illuminate\Http\Request;

class ActivityHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ActivityHistoriesDataTable $dataTable)
    {
        if (!auth()->user()->hasRole('Super Admin')) return abort(401, __('admin.unauthorized'));

        $adv_search = array();
        $search_properties = array();
        if ($request->has('adv_search')) {
            $adv_search = json_decode($request->adv_search);

            if (is_array($adv_search)) {
                foreach ($adv_search as $key => $where) {
                    if ($where->search == 'properties') {
                        $search_properties[] = $where;
                        unset($adv_search[$key]);
                    }
                }
            }
        }

        $users = User::select('id', 'name as value')->orderBy('name')->get()->toArray();

        return $dataTable->with('adv_search', $adv_search)->with('search_properties', $search_properties)->render('admin.users.activity_history', ['users' => $users]);
    }

    /**
     * Clear db table.
     *
     * @return \Illuminate\Http\Response
     */
    public function clear()
    {
        if (!auth()->user()->hasRole('Super Admin')) return abort(401, __('admin.unauthorized'));

        try {
            Activity::truncate();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }

        activity()->log('Cleared Activity History');
        return response()->json(['message' =>  __('user.activity_history.clear.success')]);
    }
}
