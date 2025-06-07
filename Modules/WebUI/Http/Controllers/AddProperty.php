<?php

namespace Modules\WebUI\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class AddProperty extends Controller
{
    public function addproperty()
    {
        $css = ['add-property.css', 'app.css', 'components.css'];
        $js = ['add-property.js', 'components.js','map-find-adress.js'];
        $cities = [];

        return view('webui::Pages.add-property', compact('css', 'js'));
    }
}
