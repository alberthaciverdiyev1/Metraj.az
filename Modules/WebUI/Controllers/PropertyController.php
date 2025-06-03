<?php

namespace Modules\WebUI\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class PropertyController extends Controller
{


    public function listing()
    {
        $css = ['listing.css', 'app.css'];
        $js = ['listing.js'];
        $properties = get_data('property');

        return view('webui::Pages.listing', compact('css', 'js', 'properties'));
    }


    public function propertyDetail($id)
    {
        $property = get_data('property'.$id);
dd($property);
        if (!$property) {
            abort(404);
        }

        $css = [
            'app.css',
            'components.css',
            'listing-details.css'
        ];

        $js = ['listing-detail.js', 'app.js', 'gotop.js'];

        $property = $this->properties[$id];
        $property['image'] = asset($property['image']);
        $property['extra']['baths'] = $property['baths'] ?? null;

        return view('webui::Pages.property-detail', compact('css', 'js', 'property'));
    }

}
