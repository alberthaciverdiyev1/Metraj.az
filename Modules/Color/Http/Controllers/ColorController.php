<?php

namespace Modules\Color\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Nwidart\Modules\Facades\Module;

class ColorController extends Controller
{

    public function __construct()
    {
        if (Module::find('Roles')->isEnabled()) {
            $this->middleware('permission:view colors')->only('index');
            $this->middleware('permission:create color')->only('create');
            $this->middleware('permission:store color')->only('store');
            $this->middleware('permission:edit color')->only('edit');
            $this->middleware('permission:update color')->only('update');
            $this->middleware('permission:destroy color')->only('destroy');
        }
    }


    /**
    * Display a listing of the resource.
    */
    public function index()
    {
        return view('color::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('color::create');
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
        return view('color::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('color::edit');
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
