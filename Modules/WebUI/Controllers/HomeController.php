<?php

namespace Modules\WebUI\Controllers;

use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    private array $properties = [
        1 => [
            'image' => 'webui/images/box-house.jpg',
            'title' => 'Elegant studio flat',
            'address' => '102 Ingraham St, Brooklyn, NY 11237',
            'beds' => 3,
            'baths' => 3,
            'area' => '4,043',
            'price' => '8,600',
            'description' => 'This is a detailed description of the elegant studio flat...',
            'details' => [
                'units' => '3 Units in North Hollywood with upside potential through construction of an ADU (buyer to verify).',
                'unit_mix' => '(3) 3+1 bath units',
                'building_size' => '2,660 sqft',
                'lot_size' => '6,001 sqft',
                'access' => 'Easy access to the 101, 170, and 134 freeways.',
                'metering' => 'Separately metered for gas and electricity',
            ],
            'extra' => [
                'id' => '#1234',
                'price_text' => '$7,500',
                'size' => '150 sqft',
                'rooms' => 9,
                'beds_exact' => 7.328,
                'year_built' => 2022,
                'type' => 'Villa',
                'status' => 'For sale',
                'garage' => 1,
                'rent_price' => '$250,00 /month',
            ],
            'amenities' => [
                'Smoke alarm',
                'Carbon monoxide alarm',
                'First aid kit',
                'Self check-in with lockbox',
                'Security cameras',
                'Hangers',
                'Bed linens',
                'Extra pillows & blankets',
                'Iron',
                'TV with standard cable',
                'Refrigerator',
                'Microwave',
                'Dishwasher',
                'Coffee maker',
            ],
            'floor_plan' => [
                'first_floor' => '2 bedrooms, 3 bathrooms',
                'second_floor' => '3 bedrooms',
            ],
            'nearby' => [
                'School' => '0.7 km',
                'University' => '1.3 km',
                'Grocery center' => '0.6 km',
                'Market' => '1.1 km',
            ],
            'comments' => [],
        ],
        2 => [
            'image' => 'webui/images/box-house.jpg',
            'title' => 'Elegant studio flat 2',
            'address' => '103 Ingraham St, Brooklyn, NY 11237',
            'beds' => 3,
            'baths' => 3,
            'area' => '4,043',
            'price' => '8,600',
            'description' => 'This is a detailed description of the elegant studio flat...',
            'details' => [
                'units' => '3 Units in North Hollywood with upside potential through construction of an ADU (buyer to verify).',
                'unit_mix' => '(3) 3+1 bath units',
                'building_size' => '2,660 sqft',
                'lot_size' => '6,001 sqft',
                'access' => 'Easy access to the 101, 170, and 134 freeways.',
                'metering' => 'Separately metered for gas and electricity',
            ],
            'extra' => [
                'id' => '#1234',
                'price_text' => '$7,500',
                'size' => '150 sqft',
                'rooms' => 9,
                'beds_exact' => 7.328,
                'year_built' => 2022,
                'type' => 'Villa',
                'status' => 'For sale',
                'garage' => 1,
                'rent_price' => '$250,00 /month',
            ],
            'amenities' => [
                'Smoke alarm',
                'Carbon monoxide alarm',
                'First aid kit',
                'Self check-in with lockbox',
                'Security cameras',
                'Hangers',
                'Bed linens',
                'Extra pillows & blankets',
                'Iron',
                'TV with standard cable',
                'Refrigerator',
                'Microwave',
                'Dishwasher',
                'Coffee maker',
            ],
            'floor_plan' => [
                'first_floor' => '2 bedrooms, 3 bathrooms',
                'second_floor' => '3 bedrooms',
            ],
            'nearby' => [
                'School' => '0.7 km',
                'University' => '1.3 km',
                'Grocery center' => '0.6 km',
                'Market' => '1.1 km',
            ],
            'comments' => [],
        ],
    ];


    public function index()
    {
        $css = ['home.css', 'app.css'];
        $js = ['home.js'];
        $cities = [];

        return view('webui::home.index', compact('css', 'js', 'cities'));
    }

    public function listing()
    {
        $css = ['listing.css', 'app.css'];
        $js = ['listing.js'];

        // asset() tətbiqi blade faylında olacaq
        $properties = collect($this->properties)->map(function ($property, $id) {
            $property['id'] = $id;
            $property['image'] = asset($property['image']);
            return $property;
        })->values();

        return view('webui::home.listing', compact('css', 'js', 'properties'));
    }

    public function agencies()
    {
        $css = ['agencies.css', 'app.css', 'listing.css'];
        $js = ['agencies.js', 'listing.js'];

        return view('webui::home.agencies', compact('css', 'js'));
    }

  public function propertyDetail($id)
{
    if (!isset($this->properties[$id])) {
        abort(404);
    }

    $css = ['property-detail.css', 'app.css']; // buraya stil fayllarını əlavə et
    $js = ['property-detail.js', 'app.js'];    // lazım olsa js də

    $property = $this->properties[$id];
    $property['image'] = asset($property['image']);

    return view('webui::home.property-detail', compact('css', 'js', 'property'));
}

}
