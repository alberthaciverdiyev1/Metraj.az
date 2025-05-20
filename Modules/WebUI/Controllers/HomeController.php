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
            'beds' => 4,
            'baths' => 3,
            'area' => '4,043',
            'price' => '8,600',
            'video' => 'https://www.youtube.com/embed/MLpWrANjFbI',

            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam risus leo, blandit vitae diam a, vestibulum viverra nisi. Vestibulum ullamcorper velit eget mattis aliquam. Proin dapibus luctus pulvinar. Integer et libero ut purus bibendum gravida non ac tellus.

Aliquam non lorem consequat, luctus dui et, auctor nisi. Aenean placerat sapien at augue lacinia, non semper urna tempor. Mauris sit amet elit orci',
            'altimages' => [
                'first_image' => 'webui/images/property-detail-3.jpg',
                'second_image' => 'webui/images/property-detail-4.jpg',
                'third_image' => 'webui/images/property-detail-5.jpg',
                'fourth_image' => 'webui/images/property-detail-6.jpg'
            ],
            'details' => [
                'units' => '3 Units in North Hollywood with upside potential through construction of an ADU (buyer to verify).',
                'unit_mix' => '(3) 3+1 bath units',
                'building_size' => '2,660 sqft',
                'lot_size' => '6,001 sqft',
                'access' => 'Easy access to the 101, 170, and 134 freeways.',
                'metering' => 'Separately metered for gas and electricity',
            ],
            'extra' => [
                'id' => '1234',
                'price_text' => '$7,500',
                'size' => '150 sqft',
                'rooms' => 9,
                'beds_exact' => 7.328,
                'year_built' => 2022,
                'type' => 'Villa',
                'status' => 'For sale',
                'garage' => 3,
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
                [
                    'floor' => 'First Floor',
                    'bedrooms' => 2,
                    'bathrooms' => 3,
                    'image' => 'https://themesflat.co/html/proty/images/section/floor.jpg',
                ],
                [
                    'floor' => 'Second Floor',
                    'bedrooms' => 1,
                    'bathrooms' => 5,
                    'image' => 'https://themesflat.co/html/proty/images/section/floor.jpg',
                ],
            ],

            'nearby' => [

                'School' => '0.7 km',
                'University' => '1.3 km',
                'Grocery center' => '0.6 km',
                'Market' => '1.1 km',
            ],
            'map' => [
                'latitude' => 40.5812895,
                'longitude' => 49.6735533,
                'address' => 'Sumgait beach',
                'city' => 'Sumgait',
                'state' => 'Absheron',
                'postal_code' => 'AZ5000',
                'area' => 7345,
                'country' => 'Azerbaijan',
            ],
            'virtual_tour' => [
                'link' => 'https://www.youtube.com/embed/MLpWrANjFbI',
                'image' => 'https://themesflat.co/html/proty/images/section/property-detail-2.jpg',
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

        $agencies = [
            1 => [
                'name' => 'Lorem House',
                'address' => '2118 Thornridge Cir. Syracuse, Connecticut 35624',
                'location' => '102 Ingraham St, Brooklyn, NY 11237',
                'listing_count' => 7.328,
                'image' => 'https://themesflat.co/html/proty/images/section/agencies-1.jpg',
                'hotline' => '+7-445-556-8337',
                'phone' => '+7-445-556-8337',
                'email' => 'loremhouse@gmail.com',
                'about' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam risus leo, blandit vitae diam a, vestibulum viverra nisi...',
                'logo' => 'https://themesflat.co/html/proty/images/brands/brand-7.jpg',
            ],
            2 => [
                'name' => 'Lorem House-2',
                'address' => '2118 Thornridge Cir. Syracuse, Connecticut 35624',
                'location' => '102 Ingraham St, Brooklyn, NY 11237',
                'listing_count' => 9.328,
                'hotline' => '+7-445-556-8337',
                'phone' => '+7-445-556-8337',
                'email' => 'loremhouse@gmail.com',
                'image' => 'https://themesflat.co/html/proty/images/section/agencies-2.jpg',
                'about' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam risus leo, blandit vitae diam a, vestibulum viverra nisi...',
                'logo' => 'https://themesflat.co/html/proty/images/brands/brand-1.jpg',
            ]
        ];

        return view('webui::home.agencies', compact('css', 'js', 'agencies'));
    }


    public function propertyDetail($id)
    {
        if (!isset($this->properties[$id])) {
            abort(404);
        }

        $css = ['listing-details.css', 'app.css', 'components.css'];
        $js = ['listing-detail.js', 'app.js', 'gotop.js'];

        $property = $this->properties[$id];
        $property['image'] = asset($property['image']);

        $property['extra']['baths'] = $property['baths'] ?? null;

        return view('webui::home.property-detail', compact('css', 'js', 'property'));
    }
    public function agencyDetail($id)
    {
        $agencies = [
            1 => [
                'name' => 'Lorem House',
                'address' => '2118 Thornridge Cir. Syracuse, Connecticut 35624',
                'location' => '102 Ingraham St, Brooklyn, NY 11237',
                'listing_count' => 7.328,
                'hotline' => '+7-445-556-8337',
                'phone' => '+7-445-556-8337',
                'image' => 'https://themesflat.co/html/proty/images/section/agencies-1.jpg',
                'email' => 'loremhouse@gmail.com',
                'about' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam risus leo, blandit vitae diam a, vestibulum viverra nisi...',
                'logo' => 'https://themesflat.co/html/proty/images/brands/brand-7.jpg',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam risus leo, blandit vitae diam a, vestibulum viverra nisi. Vestibulum ullamcorper velit eget mattis aliquam. Proin dapibus luctus pulvinar. Integer et libero ut purus bibendum gravida non ac tellus.

Aliquam non lorem consequat, luctus dui et, auctor nisi. Aenean placerat sapien at augue lacinia, non semper urna tempor. Mauris sit amet elit orci',
            ],
            2 => [
                'name' => 'Lorem House-2',
                'address' => '2118 Thornridge Cir. Syracuse, Connecticut 35624',
                'location' => '102 Ingraham St, Brooklyn, NY 11237',
                'listing_count' => 9.328,
                'hotline' => '+7-445-556-8337',
                'phone' => '+7-445-556-8337',
                'image' => 'https://themesflat.co/html/proty/images/section/agencies-2.jpg',
                'email' => 'loremhouse@gmail.com',
                'about' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam risus leo, blandit vitae diam a, vestibulum viverra nisi...',
                'logo' => 'https://themesflat.co/html/proty/images/brands/brand-1.jpg',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam risus leo, blandit vitae diam a, vestibulum viverra nisi...',
            ]
        ];

        if (!isset($agencies[$id])) {
            abort(404);
        }

        $css = ['agency-detail.css', 'app.css', 'components.css', 'agencies.css'];
        $js = ['agency-detail.js', 'app.js'];

        $agency = $agencies[$id];
        $properties = collect($this->properties)
            ->map(function ($property, $id) {
                $property['id'] = $id;
                $property['image'] = asset($property['image']);
                return $property;
            })
            ->take(5)
            ->values();
        return view('webui::home.agency-detail', compact('css', 'js', 'agency', 'properties'));
    }
}
