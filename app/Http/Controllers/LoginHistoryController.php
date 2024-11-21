<?php

namespace App\Http\Controllers;

use App\Models\LoginHistory;
use App\Models\User;
use App\DataTables\LoginHistoriesDataTable;
use Illuminate\Http\Request;

class LoginHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, LoginHistoriesDataTable $dataTable)
    {
        if (!auth()->user()->hasRole('Super Admin')) return abort(401, __('admin.unauthorized'));

        $adv_search = array();
        if ($request->has('adv_search')) {
            $adv_search = json_decode($request->adv_search);
        }

        $device_types = array(
            array('id' => 'mobile', 'value' => 'Mobile'),
            array('id' => 'tablet', 'value' => 'Tablet'),
            array('id' => 'desktop', 'value' => 'Desktop'),
            array('id' => 'bot', 'value' => 'Bot'),
        );

        $users = User::select('id', 'name as value')->orderBy('name')->get()->toArray();

        return $dataTable->with('adv_search', $adv_search)->render('admin.users.login_history', ['device_types' => $device_types, 'users' => $users]);
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
            LoginHistory::truncate();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }

        activity()->log('Cleared Login History');
        return response()->json(['message' =>  __('user.login_history.clear.success')]);
    }
}
