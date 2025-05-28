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
        2 => [
            'image' => 'webui/images/box-house-5.jpg',
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
        3 => [
            'image' => 'webui/images/box-house-6.jpg',
            'title' => 'Elegant studio flat-3',
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
        4 => [
            'image' => 'webui/images/box-house-6.jpg',
            'title' => 'Elegant studio flat-3',
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
        5 => [
            'image' => 'webui/images/box-house-5.jpg',
            'title' => 'Elegant studio flat-5',
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
        6 => [
            'image' => 'webui/images/box-house-6.jpg',
            'title' => 'Elegant studio flat-6',
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
        7 => [
            'image' => 'https://themesflat.co/html/proty/images/section/box-house-16.jpg',
            'title' => 'Elegant studio flat-7',
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
        8 => [
            'image' => 'https://themesflat.co/html/proty/images/section/box-house-16.jpg',
            'title' => 'Elegant studio flat-7',
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
        9 => [
            'image' => 'https://themesflat.co/html/proty/images/section/box-house-16.jpg',
            'title' => 'Elegant studio flat-7',
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
        10 => [
            'image' => 'https://themesflat.co/html/proty/images/section/box-house-16.jpg',
            'title' => 'Elegant studio flat-7',
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

        11 => [
            'image' => 'https://themesflat.co/html/proty/images/section/box-house-16.jpg',
            'title' => 'Elegant studio flat-7',
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
        12 => [
            'image' => 'https://themesflat.co/html/proty/images/section/box-house-16.jpg',
            'title' => 'Elegant studio flat-7',
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
    private  $blog = [
        1 => [
            'title' => 'Building gains into housing stocks and how to trade the sector',
            'date' => '26 August, 2024',
            'category' => 'Real Estate',
            'author' => 'Kathryn Murphy',
            'description' => 'The housing sector has long been a focal point for investors seeking stability and growth. Understanding the dynamics of housing stocks and effectively trading within this sector can lead to substantial gains.',
            'images' => [
                'https://themesflat.co/html/proty/images/blog/blog-grid-1.jpg',
                'https://themesflat.co/html/proty/images/blog/blog-grid-2.jpg',
                'https://themesflat.co/html/proty/images/blog/blog-grid-3.jpg',
                'https://themesflat.co/html/proty/images/blog/blog-grid-4.jpg',
            ],
            'content' => [
                "The housing sector has long been a focal point for investors seeking stability and growth. Understanding the dynamics of housing stocks and effectively trading within this sector can lead to substantial gains.",
                "Understanding Housing Stocks: Housing stocks encompass companies involved in various aspects of the real estate industry, including homebuilders, developers, and related service providers. Factors influencing these stocks range from interest rates and economic indicators to trends in homeownership rates.",
                "Pay close attention to economic indicators such as employment rates, GDP growth, and consumer confidence. A strong economy often correlates with increased demand for housing, benefiting related stocks.",
                "Identify Emerging Trends: Stay informed about emerging trends in the housing market, such as the demand for sustainable homes, technological advancements, and demographic shifts. Companies aligning with these trends may present attractive investment opportunities.",
                "Take a long-term investment approach if you believe in the stability and growth potential of the housing sector. Look for companies with solid fundamentals and a track record of success. For short-term traders, capitalize on market fluctuations driven by economic reports, interest rate changes, or industry-specific news. Keep a close eye on earnings reports and government housing data releases."
            ]
        ],

        2 => [
            'title' => 'Building gains into housing stocks and how to trade the sector',
            'date' => '27 August, 2024',
            'category' => 'Real Estate',
            'author' => 'Kathryn Murphy',
            'description' => 'The housing sector has long been a focal point for investors seeking stability and growth. Understanding the dynamics of housing stocks and effectively trading within this sector can lead to substantial gains.',
            'images' => [
                'https://themesflat.co/html/proty/images/blog/blog-grid-2.jpg',
                'https://themesflat.co/html/proty/images/blog/blog-grid-2.jpg',
                'https://themesflat.co/html/proty/images/blog/blog-grid-3.jpg',
                'https://themesflat.co/html/proty/images/blog/blog-grid-4.jpg',
            ],
            'content' => [
                "The housing sector has long been a focal point for investors seeking stability and growth. Understanding the dynamics of housing stocks and effectively trading within this sector can lead to substantial gains.",
                "Understanding Housing Stocks: Housing stocks encompass companies involved in various aspects of the real estate industry, including homebuilders, developers, and related service providers. Factors influencing these stocks range from interest rates and economic indicators to trends in homeownership rates.",
                "Pay close attention to economic indicators such as employment rates, GDP growth, and consumer confidence. A strong economy often correlates with increased demand for housing, benefiting related stocks.",
                "Identify Emerging Trends: Stay informed about emerging trends in the housing market, such as the demand for sustainable homes, technological advancements, and demographic shifts. Companies aligning with these trends may present attractive investment opportunities.",
                "Take a long-term investment approach if you believe in the stability and growth potential of the housing sector. Look for companies with solid fundamentals and a track record of success. For short-term traders, capitalize on market fluctuations driven by economic reports, interest rate changes, or industry-specific news. Keep a close eye on earnings reports and government housing data releases."
            ]
        ],
        3 => [
            'title' => 'Building gains into housing stocks and how to trade the sector',
            'date' => '27 August, 2024',
            'category' => 'Real Estate',
            'author' => 'Kathryn Murphy',
            'description' => 'The housing sector has long been a focal point for investors seeking stability and growth. Understanding the dynamics of housing stocks and effectively trading within this sector can lead to substantial gains.',
            'images' => [
                'https://themesflat.co/html/proty/images/blog/blog-grid-2.jpg',
                'https://themesflat.co/html/proty/images/blog/blog-grid-2.jpg',
                'https://themesflat.co/html/proty/images/blog/blog-grid-3.jpg',
                'https://themesflat.co/html/proty/images/blog/blog-grid-4.jpg',
            ],
            'content' => [
                "The housing sector has long been a focal point for investors seeking stability and growth. Understanding the dynamics of housing stocks and effectively trading within this sector can lead to substantial gains.",
                "Understanding Housing Stocks: Housing stocks encompass companies involved in various aspects of the real estate industry, including homebuilders, developers, and related service providers. Factors influencing these stocks range from interest rates and economic indicators to trends in homeownership rates.",
                "Pay close attention to economic indicators such as employment rates, GDP growth, and consumer confidence. A strong economy often correlates with increased demand for housing, benefiting related stocks.",
                "Identify Emerging Trends: Stay informed about emerging trends in the housing market, such as the demand for sustainable homes, technological advancements, and demographic shifts. Companies aligning with these trends may present attractive investment opportunities.",
                "Take a long-term investment approach if you believe in the stability and growth potential of the housing sector. Look for companies with solid fundamentals and a track record of success. For short-term traders, capitalize on market fluctuations driven by economic reports, interest rate changes, or industry-specific news. Keep a close eye on earnings reports and government housing data releases."
            ]
        ],
        4 => [
            'title' => 'Building gains into housing stocks and how to trade the sector',
            'date' => '28 August, 2024',
            'category' => 'News',
            'author' => 'Kathryn Murphy',
            'description' => 'The housing sector has long been a focal point for investors seeking stability and growth. Understanding the dynamics of housing stocks and effectively trading within this sector can lead to substantial gains.',
            'images' => [
                'https://themesflat.co/html/proty/images/blog/blog-grid-3.jpg',
                'https://themesflat.co/html/proty/images/blog/blog-grid-2.jpg',
                'https://themesflat.co/html/proty/images/blog/blog-grid-3.jpg',
                'https://themesflat.co/html/proty/images/blog/blog-grid-4.jpg',
            ],
            'content' => [
                "The housing sector has long been a focal point for investors seeking stability and growth. Understanding the dynamics of housing stocks and effectively trading within this sector can lead to substantial gains.",
                "Understanding Housing Stocks: Housing stocks encompass companies involved in various aspects of the real estate industry, including homebuilders, developers, and related service providers. Factors influencing these stocks range from interest rates and economic indicators to trends in homeownership rates.",
                "Pay close attention to economic indicators such as employment rates, GDP growth, and consumer confidence. A strong economy often correlates with increased demand for housing, benefiting related stocks.",
                "Identify Emerging Trends: Stay informed about emerging trends in the housing market, such as the demand for sustainable homes, technological advancements, and demographic shifts. Companies aligning with these trends may present attractive investment opportunities.",
                "Take a long-term investment approach if you believe in the stability and growth potential of the housing sector. Look for companies with solid fundamentals and a track record of success. For short-term traders, capitalize on market fluctuations driven by economic reports, interest rate changes, or industry-specific news. Keep a close eye on earnings reports and government housing data releases."
            ]
        ],
        5 => [
            'title' => 'Building gains into housing stocks and how to trade the sector',
            'date' => '23 August, 2024',
            'category' => 'News',
            'author' => 'Kathryn Murphy',
            'description' => 'The housing sector has long been a focal point for investors seeking stability and growth. Understanding the dynamics of housing stocks and effectively trading within this sector can lead to substantial gains.',
            'images' => [
                'https://themesflat.co/html/proty/images/blog/blog-grid-4.jpg',
                'https://themesflat.co/html/proty/images/blog/blog-grid-2.jpg',
                'https://themesflat.co/html/proty/images/blog/blog-grid-3.jpg',
                'https://themesflat.co/html/proty/images/blog/blog-grid-4.jpg',
            ],
            'content' => [
                "The housing sector has long been a focal point for investors seeking stability and growth. Understanding the dynamics of housing stocks and effectively trading within this sector can lead to substantial gains.",
                "Understanding Housing Stocks: Housing stocks encompass companies involved in various aspects of the real estate industry, including homebuilders, developers, and related service providers. Factors influencing these stocks range from interest rates and economic indicators to trends in homeownership rates.",
                "Pay close attention to economic indicators such as employment rates, GDP growth, and consumer confidence. A strong economy often correlates with increased demand for housing, benefiting related stocks.",
                "Identify Emerging Trends: Stay informed about emerging trends in the housing market, such as the demand for sustainable homes, technological advancements, and demographic shifts. Companies aligning with these trends may present attractive investment opportunities.",
                "Take a long-term investment approach if you believe in the stability and growth potential of the housing sector. Look for companies with solid fundamentals and a track record of success. For short-term traders, capitalize on market fluctuations driven by economic reports, interest rate changes, or industry-specific news. Keep a close eye on earnings reports and government housing data releases."
            ]
        ],
        6 => [
            'title' => 'Building gains into housing stocks and how to trade the sector',
            'date' => '28 August, 2024',
            'category' => 'News',
            'author' => 'Kathryn Murphy',
            'description' => 'The housing sector has long been a focal point for investors seeking stability and growth. Understanding the dynamics of housing stocks and effectively trading within this sector can lead to substantial gains.',
            'images' => [
                'https://themesflat.co/html/proty/images/blog/blog-grid-3.jpg',
                'https://themesflat.co/html/proty/images/blog/blog-grid-2.jpg',
                'https://themesflat.co/html/proty/images/blog/blog-grid-3.jpg',
                'https://themesflat.co/html/proty/images/blog/blog-grid-4.jpg',
            ],
            'content' => [
                "The housing sector has long been a focal point for investors seeking stability and growth. Understanding the dynamics of housing stocks and effectively trading within this sector can lead to substantial gains.",
                "Understanding Housing Stocks: Housing stocks encompass companies involved in various aspects of the real estate industry, including homebuilders, developers, and related service providers. Factors influencing these stocks range from interest rates and economic indicators to trends in homeownership rates.",
                "Pay close attention to economic indicators such as employment rates, GDP growth, and consumer confidence. A strong economy often correlates with increased demand for housing, benefiting related stocks.",
                "Identify Emerging Trends: Stay informed about emerging trends in the housing market, such as the demand for sustainable homes, technological advancements, and demographic shifts. Companies aligning with these trends may present attractive investment opportunities.",
                "Take a long-term investment approach if you believe in the stability and growth potential of the housing sector. Look for companies with solid fundamentals and a track record of success. For short-term traders, capitalize on market fluctuations driven by economic reports, interest rate changes, or industry-specific news. Keep a close eye on earnings reports and government housing data releases."
            ]
        ]
    ];
   public function index() 
{
    $css = ['home.css', 'app.css', 'components.css'];
    $js = ['home.js', 'components.js'];
    $cities = [];

    $properties = collect($this->properties)->map(function ($property, $id) {
        $property['id'] = $id;
        $property['image'] = asset($property['image']);
        return $property;
    })->values();

    return view('webui::home.index', compact('css', 'js', 'cities', 'properties'));
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
            ],
            3 => [
                'name' => 'Lorem House-3',
                'address' => '2118 Thornridge Cir. Syracuse, Connecticut 35624',
                'location' => '102 Ingraham St, Brooklyn, NY 11237',
                'listing_count' => 9.328,
                'hotline' => '+7-445-556-8337',
                'phone' => '+7-445-556-8337',
                'email' => 'loremhouse@gmail.com',
                'image' => 'https://themesflat.co/html/proty/images/section/agencies-3.jpg',
                'about' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam risus leo, blandit vitae diam a, vestibulum viverra nisi...',
                'logo' => 'https://themesflat.co/html/proty/images/brands/brand-1.jpg',
            ],
            4 => [
                'name' => 'Lorem House-4',
                'address' => '2118 Thornridge Cir. Syracuse, Connecticut 35624',
                'location' => '102 Ingraham St, Brooklyn, NY 11237',
                'listing_count' => 9.328,
                'hotline' => '+7-445-556-8337',
                'phone' => '+7-445-556-8337',
                'email' => 'loremhouse@gmail.com',
                'image' => 'https://themesflat.co/html/proty/images/section/agencies-4.jpg',
                'about' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam risus leo, blandit vitae diam a, vestibulum viverra nisi...',
                'logo' => 'https://themesflat.co/html/proty/images/brands/brand-1.jpg',
            ],
            5 => [
                'name' => 'Lorem House-3',
                'address' => '2118 Thornridge Cir. Syracuse, Connecticut 35624',
                'location' => '102 Ingraham St, Brooklyn, NY 11237',
                'listing_count' => 9.328,
                'hotline' => '+7-445-556-8337',
                'phone' => '+7-445-556-8337',
                'email' => 'loremhouse@gmail.com',
                'image' => 'https://themesflat.co/html/proty/images/section/agencies-5.jpg',
                'about' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam risus leo, blandit vitae diam a, vestibulum viverra nisi...',
                'logo' => 'https://themesflat.co/html/proty/images/brands/brand-1.jpg',
            ],
            6 => [
                'name' => 'Lorem House-3',
                'address' => '2118 Thornridge Cir. Syracuse, Connecticut 35624',
                'location' => '102 Ingraham St, Brooklyn, NY 11237',
                'listing_count' => 9.328,
                'hotline' => '+7-445-556-8337',
                'phone' => '+7-445-556-8337',
                'email' => 'loremhouse@gmail.com',
                'image' => 'https://themesflat.co/html/proty/images/section/agencies-.jpg',
                'about' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam risus leo, blandit vitae diam a, vestibulum viverra nisi...',
                'logo' => 'https://themesflat.co/html/proty/images/brands/brand-1.jpg',
            ]
        ];

        return view('webui::Pages.agencies', compact('css', 'js', 'agencies'));
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
        $js = ['agency-detail.js', 'app.js', 'gotop.js'];

        $agency = $agencies[$id];
        $properties = collect($this->properties)
            ->map(function ($property, $id) {
                $property['id'] = $id;
                $property['image'] = asset($property['image']);
                return $property;
            })
            ->take(limit: 8)
            ->values();
        return view('webui::Pages.agency-detail', compact('css', 'js', 'agency', 'properties'));
    }
    public function contact()
    {
        $css = ['contact.css', 'app.css', 'components.css', 'agencies.css'];
        $js = ['contact.js', 'gotop.js'];

        return view('webui::Pages.contact', compact('css', 'js'));
    }


    public function faqs()
    {
        $css = ['faqs.css', 'app.css', 'components.css', 'listing-details.css', 'agencies.css'];
        $js = ['faqs.js', 'gotop.js'];
        $cities = [];
        return view('webui::Pages.faqs', compact('css', 'js'));
    }

    public function blog()
    {
        $css = ['blog.css', 'app.css', 'components.css', 'listing-details.css', 'agencies.css'];
        $js = ['blog.js', 'gotop.js'];
        $cities = [];

        $blog = $this->blog;


        return view('webui::Pages.blog', compact('css', 'js', 'cities', 'blog'));
    }

    public function blogDetail($id)
    {
        if (!isset($this->blog[$id])) {
            abort(404);
        }

        $css = ['blog-detail.css', 'app.css', 'components.css', 'listing-details.css', 'agencies.css', 'blog.css'];
        $js = ['blog-detail.js', 'gotop.js'];

        $blog = $this->blog[$id];


        $relatedPosts = array_filter($this->blog, function ($key) use ($id) {
            return $key != $id;
        }, ARRAY_FILTER_USE_KEY);

        $relatedPosts = array_slice($relatedPosts, 0, 3);

        return view('webui::Pages.blog-detail', compact('css', 'js', 'blog', 'relatedPosts'));
    }
   public function comingSoon()
    {
        $css = ['coming-soon.css', 'app.css'];
        $js = ['coming-soon.js'];
        $cities = [];
        return view('webui::Pages.coming-soon', compact('css', 'js'));
    }
  
}
