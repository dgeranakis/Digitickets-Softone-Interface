<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\DataTables\UsersDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, UsersDataTable $dataTable)
    {
        if (!auth()->user()->hasRole('Super Admin')) return abort(401, __('admin.unauthorized'));

        $adv_search = array();
        $search_roles = array();
        if ($request->has('adv_search')) {
            $adv_search = json_decode($request->adv_search);

            if (is_array($adv_search)) {
                foreach ($adv_search as $key => $where) {
                    if ($where->search == 'roles') {
                        $search_roles[] = $where;
                        unset($adv_search[$key]);
                    }
                }
            }
        }

        $roles = Role::select('name as value')->orderBy('name')->get()->toArray();
        return $dataTable->with('adv_search', $adv_search)->with('search_roles', $search_roles)->with('roles', $roles)->render('admin.users.index', ['roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->hasRole('Super Admin')) return abort(401, __('admin.unauthorized'));

        return view('admin.users.create')->with([
            'roles' => Role::select('name as value')->orderBy('name')->get()->toArray()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        if (!auth()->user()->hasRole('Super Admin')) return response()->json(['message' =>  __('admin.unauthorized')], 401);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'active' => $request->active,
            'is_admin' => $request->is_admin,
            'lines_per_page' => $request->lines_per_page,
            'email_verified_at' => date('Y-m-d H:i:s'),
            'created_by_user' => auth()->user()->id,
            'updated_by_user' => auth()->user()->id
        ];

        $user = new User($data);
        $user->save();

        $roles = $request->roles ? $request->roles : [];
        $user->assignRole($roles);

        return response()->json(['message' =>  __('user.create.success')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if (!auth()->user()->hasRole('Super Admin')) return abort(401, __('admin.unauthorized'));

        return view('admin.users.edit', compact('user'))->with([
            'roles' => Role::select('name as value')->orderBy('name')->get()->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if (!auth()->user()->hasRole('Super Admin')) return response()->json(['message' =>  __('admin.unauthorized')], 401);

        $user->name = $request->name;
        $user->email = $request->email;
        if (filled($request->password)) $user->password = Hash::make($request->password);
        $user->active = $request->active;
        $user->is_admin = $request->is_admin;
        $user->lines_per_page = $request->lines_per_page;
        $user->updated_by_user = auth()->user()->id;
        $user->save();

        $roles = $request->roles ? $request->roles : [];
        $user->syncRoles($roles);

        return response()->json(['message' =>  __('user.edit.success')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (!auth()->user()->hasRole('Super Admin')) return response()->json(['message' =>  __('admin.unauthorized')], 401);

        try {
            $user->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") //23000 is sql code for integrity constraint violation
            {
                return response()->json(['message' => __('admin.integrity_const_violation')], 401);
            } else {
                return response()->json(['message' => $e->getMessage()], 401);
            }
        }

        return response()->json(['message' =>  __('user.delete.success')]);
    }
}
