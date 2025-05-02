<?php

namespace Modules\Town\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Town\Http\Entities\Town;
use Nwidart\Modules\Facades\Module;

class TownController extends Controller
{

//    public function __construct()
//    {
//        if (Module::find('Roles')->isEnabled()) {
//            $this->middleware('permission:view towns')->only('index');
//            $this->middleware('permission:create town')->only('create');
//            $this->middleware('permission:store town')->only('store');
//            $this->middleware('permission:edit town')->only('edit');
//            $this->middleware('permission:update town')->only('update');
//            $this->middleware('permission:destroy town')->only('destroy');
//        }
//    }


    /**
    * Display a listing of the resource.
    */
    public function list()
    {
        $towns = Town::with('district')->get();
        return $towns;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('town::create');
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
        return view('town::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('town::edit');
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
