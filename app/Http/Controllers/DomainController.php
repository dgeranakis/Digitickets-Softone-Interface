<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Http\Requests\StoreDomainRequest;
use App\Http\Requests\UpdateDomainRequest;
use App\DataTables\DomainsDataTable;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, DomainsDataTable $dataTable)
    {
        if (!auth()->user()->hasRole('Super Admin')) return abort(401, __('admin.unauthorized'));

        $adv_search = array();
        if ($request->has('adv_search')) {
            $adv_search = json_decode($request->adv_search);
        }

        return $dataTable->with('adv_search', $adv_search)->render('admin.domains.index');
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
     * @param  \App\Http\Requests\StoreDomainRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDomainRequest $request)
    {
        if (!auth()->user()->hasRole('Super Admin')) return response()->json(['message' =>  __('admin.unauthorized')], 401);

        $data = [
            'code' => $request->code,
            'created_by_user' => auth()->user()->id,
            'updated_by_user' => auth()->user()->id
        ];

        foreach (config('app.locales') as $lang => $locale_name) {
            $data[$lang] = ['description' => ($lang == appLocale() ? $request->description : '')];
        }

        $domain = new Domain($data);
        $domain->save();

        return response()->json(['message' => __('domain.create.success')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function show(Domain $domain)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function edit(Domain $domain)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDomainRequest  $request
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDomainRequest $request, Domain $domain)
    {
        if (!auth()->user()->hasRole('Super Admin')) return response()->json(['message' =>  __('admin.unauthorized')], 401);

        $domain->code = $request->code;
        $domain->translate(appLocale())->description = $request->description;
        $domain->updated_by_user = auth()->user()->id;
        $domain->save();

        return response()->json(['message' => __('domain.edit.success')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function destroy(Domain $domain)
    {
        if (!auth()->user()->hasRole('Super Admin')) return response()->json(['message' =>  __('admin.unauthorized')], 401);

        try {
            $domain->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") //23000 is sql code for integrity constraint violation
            {
                return response()->json(['message' => __('admin.integrity_const_violation')], 401);
            } else {
                return response()->json(['message' => $e->getMessage()], 401);
            }
        }

        return response()->json(['message' => __('domain.delete.success')]);
    }
}
