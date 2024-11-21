<?php

namespace App\Http\Controllers;

use App\Models\Enumeration;
use App\Models\Domain;
use App\Http\Requests\StoreEnumerationRequest;
use App\Http\Requests\UpdateEnumerationRequest;
use App\DataTables\EnumerationsDataTable;
use Illuminate\Http\Request;

class EnumerationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, EnumerationsDataTable $dataTable) // 
    {
        if (!auth()->user()->can('view selection lists')) return abort(401, __('admin.unauthorized'));

        $adv_search = array();
        if ($request->has('adv_search')) {
            $adv_search = json_decode($request->adv_search);
        }

        $domains = Domain::orderByTranslation('description')->select('domains.id', 'domain_translations.description as value')->get()->toArray();
        return $dataTable->with('adv_search', $adv_search)->with('domains', $domains)->render('admin.enumerations.index', ['domains' => $domains]);
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
     * @param  \App\Http\Requests\StoreEnumerationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEnumerationRequest $request)
    {
        if (!auth()->user()->can('create selection lists')) return response()->json(['message' =>  __('admin.unauthorized')], 401);

        $data = [
            'domain_id' => $request->domain,
            'code' => $request->code,
            'active' => $request->active,
            'created_by_user' => auth()->user()->id,
            'updated_by_user' => auth()->user()->id
        ];

        foreach (config('app.locales') as $lang => $locale_name) {
            $data[$lang] = ['description' => ($lang == appLocale() ? $request->description : '')];
        }

        $enumeration = new Enumeration($data);
        $enumeration->save();

        return response()->json(['message' => __('enumeration.create.success')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Enumeration  $enumeration
     * @return \Illuminate\Http\Response
     */
    public function show(Enumeration $enumeration)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Enumeration  $enumeration
     * @return \Illuminate\Http\Response
     */
    public function edit(Enumeration $enumeration)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEnumerationRequest  $request
     * @param  \App\Models\Enumeration  $enumeration
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEnumerationRequest $request, Enumeration $enumeration)
    {
        if (!auth()->user()->can('edit selection lists')) return response()->json(['message' =>  __('admin.unauthorized')], 401);

        $enumeration->domain_id = $request->domain;
        $enumeration->code = $request->code;
        $enumeration->active = $request->active;
        $enumeration->translate(appLocale())->description = $request->description;
        $enumeration->updated_by_user = auth()->user()->id;
        $enumeration->save();

        return response()->json(['message' => __('enumeration.edit.success')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Enumeration  $enumeration
     * @return \Illuminate\Http\Response
     */
    public function destroy(Enumeration $enumeration)
    {
        if (!auth()->user()->can('delete selection lists')) return response()->json(['message' =>  __('admin.unauthorized')], 401);

        try {
            $enumeration->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") //23000 is sql code for integrity constraint violation
            {
                return response()->json(['message' => __('admin.integrity_const_violation')], 401);
            } else {
                return response()->json(['message' => $e->getMessage()], 401);
            }
        }

        return response()->json(['message' => __('enumeration.delete.success')]);
    }
}
