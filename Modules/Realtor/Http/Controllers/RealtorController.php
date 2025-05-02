<?php

namespace Modules\Realtor\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Realtor\Http\Entities\Realtor;
use Nwidart\Modules\Facades\Module;

class RealtorController extends Controller
{

    public function __construct()
    {
//        if (Module::find('Roles')->isEnabled()) {
//            $this->middleware('permission:view realtors')->only('index');
//            $this->middleware('permission:create realtor')->only('create');
//            $this->middleware('permission:store realtor')->only('store');
//            $this->middleware('permission:edit realtor')->only('edit');
//            $this->middleware('permission:update realtor')->only('update');
//            $this->middleware('permission:destroy realtor')->only('destroy');
//        }
    }


    /**
    * Display a listing of the resource.
    */
    public function list()
    {
        $realtors = Realtor::all();
        return $realtors;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('realtor::create');
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
        return view('realtor::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('realtor::edit');
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
