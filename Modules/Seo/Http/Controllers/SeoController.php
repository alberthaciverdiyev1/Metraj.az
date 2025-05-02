<?php

namespace Modules\Seo\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Seo\Http\Entities\Seo;
use Nwidart\Modules\Facades\Module;

class SeoController extends Controller
{

    public function __construct()
    {
//        if (Module::find('Roles')->isEnabled()) {
//            $this->middleware('permission:view seos')->only('index');
//            $this->middleware('permission:create seo')->only('create');
//            $this->middleware('permission:store seo')->only('store');
//            $this->middleware('permission:edit seo')->only('edit');
//            $this->middleware('permission:update seo')->only('update');
//            $this->middleware('permission:destroy seo')->only('destroy');
//        }
    }


    /**
    * Display a listing of the resource.
    */
    public function list()
    {
        $seos = Seo::all();
        return $seos;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('seo::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            //TODO:STORE FUNCTIONS

            return response()->json(__('Data successfully created!'));
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show()
    {
        return view('seo::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('seo::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {

            //TODO:UPDATE FUNCTIONS

            return response()->json(__('Data successfully updated!'));
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        try {

            //TODO:DESTROY FUNCTIONS

            return response()->json(__('Data successfully deleted!'));
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
