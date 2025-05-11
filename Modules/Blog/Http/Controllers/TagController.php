<?php

namespace Modules\Blog\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Blog\Http\Entities\Blog;
use Modules\Blog\Http\Entities\Tag;
use Modules\Blog\Http\Transformers\BlogDetailsResource;
use Modules\Blog\Http\Transformers\BlogListResource;
use Modules\Keyword\Http\Entities\Keyword;
use Nwidart\Modules\Facades\Module;

class TagController extends Controller
{

    public function __construct()
    {
//        if (Module::find('Roles')->isEnabled()) {
//            $this->middleware('permission:view blogs')->only('index');
//            $this->middleware('permission:create blog')->only('create');
//            $this->middleware('permission:store blog')->only('store');
//            $this->middleware('permission:edit blog')->only('edit');
//            $this->middleware('permission:update blog')->only('update');
//            $this->middleware('permission:destroy blog')->only('destroy');
//        }
    }




    public function tags()
    {
        $tags = Tag::all();
        return $tags;
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
        return view('blog::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('blog::edit');
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
