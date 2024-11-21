<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\DataTables\PermissionsDataTable;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, PermissionsDataTable $dataTable) // 
    {
        if (!auth()->user()->hasRole('Super Admin')) return abort(401, __('admin.unauthorized'));

        $adv_search = array();
        if ($request->has('adv_search')) {
            $adv_search = json_decode($request->adv_search);
        }

        return $dataTable->with('adv_search', $adv_search)->render('admin.permissions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePermissionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePermissionRequest $request)
    {
        if (!auth()->user()->hasRole('Super Admin')) return response()->json(['message' =>  __('admin.unauthorized')], 401);

        $permission = new Permission(['name' => $request->name, 'created_by_user' => auth()->user()->id, 'updated_by_user' => auth()->user()->id]);
        $permission->save();

        return response()->json(['message' => __('permission.create.success')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePermissionRequest  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        if (!auth()->user()->hasRole('Super Admin')) return response()->json(['message' =>  __('admin.unauthorized')], 401);

        $permission->name = $request->name;
        $permission->updated_by_user = auth()->user()->id;
        $permission->save();

        return response()->json(['message' => __('permission.edit.success')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        if (!auth()->user()->hasRole('Super Admin')) return response()->json(['message' =>  __('admin.unauthorized')], 401);

        try {
            $permission->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") //23000 is sql code for integrity constraint violation
            {
                return response()->json(['message' => __('admin.integrity_const_violation')], 401);
            } else {
                return response()->json(['message' => $e->getMessage()], 401);
            }
        }

        return response()->json(['message' => __('permission.delete.success')]);
    }
}
