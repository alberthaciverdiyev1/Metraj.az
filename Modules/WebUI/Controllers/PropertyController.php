<?php

namespace Modules\WebUI\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class PropertyController extends Controller
{
    public function properties(Request $request)
    {
        $params = $request->only([
            'type',
            'addNo',
            'townId',
            'subwayId',
            'districtId',
            'cityId',
            'propertyType',
            'addType',
            'numberOfFloors',
            'numberOfRooms',
            'floorLocated',
            'area',
            'fieldArea',
            'inCredit',
        ]);

        return get_data('/property', $params, false, false, false);
    }

    public function listing()
    {
        $css = ['listing.css', 'app.css'];
        $js = ['listing.js'];

        $properties = collect($this->properties)->map(function ($property, $id) {
            $property['id'] = $id;
            $property['image'] = asset($property['image']);
            return $property;
        })->values();

        return view('webui::Pages.listing', compact('css', 'js', 'properties'));
    }


    public function propertyDetail($id)
    {
        if (!isset($this->properties[$id])) {
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
