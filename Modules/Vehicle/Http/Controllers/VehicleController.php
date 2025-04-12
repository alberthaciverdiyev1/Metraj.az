<?php

namespace Modules\Vehicle\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Nwidart\Modules\Facades\Module;

class VehicleController extends Controller
{

    public function __construct()
    {
        if (Module::find('Roles')->isEnabled()) {
            $this->middleware('permission:view vehicles')->only('index');
            $this->middleware('permission:create vehicle')->only('create');
            $this->middleware('permission:store vehicle')->only('store');
            $this->middleware('permission:edit vehicle')->only('edit');
            $this->middleware('permission:update vehicle')->only('update');
            $this->middleware('permission:destroy vehicle')->only('destroy');
        }
    }


    /**
    * Display a listing of the resource.
    */
    public function index()
    {
        return view('vehicle::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vehicle::create');
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
        return view('vehicle::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('vehicle::edit');
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
