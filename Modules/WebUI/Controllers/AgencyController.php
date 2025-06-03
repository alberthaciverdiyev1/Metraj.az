<?php

namespace Modules\WebUI\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class AgencyController extends Controller
{
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

}
