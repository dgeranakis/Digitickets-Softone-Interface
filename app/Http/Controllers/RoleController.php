<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\DataTables\RolesDataTable;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, RolesDataTable $dataTable)
    {
        if (!auth()->user()->hasRole('Super Admin')) return abort(401, __('admin.unauthorized'));

        $adv_search = array();
        $search_permissions = array();
        if ($request->has('adv_search')) {
            $adv_search = json_decode($request->adv_search);

            if (is_array($adv_search)) {
                foreach ($adv_search as $key => $where) {
                    if ($where->search == 'permissions') {
                        $search_permissions[] = $where;
                        unset($adv_search[$key]);
                    }
                }
            }
        }

        $permissions = Permission::select('name as value')->orderBy('name')->get()->toArray();
        return $dataTable->with('adv_search', $adv_search)->with('search_permissions', $search_permissions)->with('permissions', $permissions)->render('admin.roles.index', ['permissions' => $permissions]);
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
     * @param  \App\Http\Requests\StoreRoleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleRequest $request)
    {
        if (!auth()->user()->hasRole('Super Admin')) return response()->json(['message' =>  __('admin.unauthorized')], 401);

        $role = new Role(['name' => $request->name, 'created_by_user' => auth()->user()->id, 'updated_by_user' => auth()->user()->id]);
        $role->save();

        $permissions = $request->permissions ? $request->permissions : [];
        $role->givePermissionTo($permissions);

        return response()->json(['message' => __('role.create.success')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRoleRequest  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        if (!auth()->user()->hasRole('Super Admin')) return response()->json(['message' =>  __('admin.unauthorized')], 401);

        $role->name = $request->name;
        $role->updated_by_user = auth()->user()->id;
        $role->save();

        $permissions = $request->permissions ? $request->permissions : [];
        $role->syncPermissions($permissions);

        return response()->json(['message' => __('role.edit.success')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if (!auth()->user()->hasRole('Super Admin')) return response()->json(['message' =>  __('admin.unauthorized')], 401);

        try {
            $role->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") //23000 is sql code for integrity constraint violation
            {
                return response()->json(['message' => __('admin.integrity_const_violation')], 401);
            } else {
                return response()->json(['message' => $e->getMessage()], 401);
            }
        }

        return response()->json(['message' => __('role.delete.success')]);
    }
}
