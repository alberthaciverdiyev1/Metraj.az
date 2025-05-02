<?php

namespace Modules\Indicator\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Indicator\Http\Entities\Indicator;
use Nwidart\Modules\Facades\Module;

class IndicatorController extends Controller
{

    public function __construct()
    {
//        if (Module::find('Roles')->isEnabled()) {
//            $this->middleware('permission:view indicators')->only('index');
//            $this->middleware('permission:create indicator')->only('create');
//            $this->middleware('permission:store indicator')->only('store');
//            $this->middleware('permission:edit indicator')->only('edit');
//            $this->middleware('permission:update indicator')->only('update');
//            $this->middleware('permission:destroy indicator')->only('destroy');
//        }
    }


    /**
    * Display a listing of the resource.
    */
    public function list()
    {
        $indicators = Indicator::all();
        return $indicators;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('indicator::create');
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
        return view('indicator::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('indicator::edit');
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
