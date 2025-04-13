<?php

namespace Modules\Feature\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Feature\Http\Entities\Feature;
use Modules\Feature\Transformers\FeatureResource;
use Nwidart\Modules\Facades\Module;

class FeatureController extends Controller
{

    /**
    * Display a listing of the resource.
    */
    public function index()
    {
        $features = Feature::all();
        return FeatureResource::collection($features);
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
        return view('feature::show');
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
