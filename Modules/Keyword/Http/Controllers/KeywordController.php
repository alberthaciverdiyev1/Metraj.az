<?php

namespace Modules\Keyword\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Keyword\Http\Entities\Keyword;
use Nwidart\Modules\Facades\Module;

class KeywordController extends Controller
{

    public function __construct()
    {
//        if (Module::find('Roles')->isEnabled()) {
//            $this->middleware('permission:view keywords')->only('index');
//            $this->middleware('permission:create keyword')->only('create');
//            $this->middleware('permission:store keyword')->only('store');
//            $this->middleware('permission:edit keyword')->only('edit');
//            $this->middleware('permission:update keyword')->only('update');
//            $this->middleware('permission:destroy keyword')->only('destroy');
//        }
    }


    /**
    * Display a listing of the resource.
    */
    public function list()
    {
        $keywords = Keyword::with(['city','town','district','subway'])->get();

        return $keywords;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('keyword::create');
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
        return view('keyword::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('keyword::edit');
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
