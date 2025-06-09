<?php

namespace Modules\WebUI\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

class PropertyController extends Controller
{
    public function properties(Request $request)
    {
        $params = [
            'type' => $request->query('type'),
            'add_no' => $request->query('addNo'),
            'town_id' => $request->query('townId'),
            'subway_id' => $request->query('subwayId'),
            'district_id' => $request->query('districtId'),
            'city_id' => $request->query('cityId'),
            'property_type' => $request->query('propertyType'),
            'add_type' => $request->query('addType'),
            'number_of_floors' => $request->query('numberOfFloors'),
            'number_of_rooms' => $request->query('numberOfRooms'),
            'floor_located' => $request->query('floorLocated'),
            'area' => $request->query('area'),
            'field_area' => $request->query('fieldArea'),
            'in_credit' => $request->query('inCredit'),
        ];

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
