<?php

namespace Modules\WebUI\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
   public function index()
{
    $response = Http::get(config('app.api_url') . '/api/city')->json();
    $cities = $response['data'] ?? [];
    $features = get_data('feature');
    $rooms = get_data('room-count',[],true);
    $property_types = get_data('property-types',[],true);
    $css = ['home.css', 'app.css', 'components.css'];
    $js = ['home.js', 'components.js'];

    $properties = get_data('property',[
        'with'  => 'city,town,district,subway,user,realtor,media',
        'limit' => 15,
        'page'  => request('page', 1),
    ]);


    return view('webui::home.index', compact('css', 'js', 'cities', 'properties', 'features','rooms', 'property_types'));
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

   public function comingSoon()
    {
        $css = ['coming-soon.css', 'app.css'];
        $js = ['coming-soon.js'];
        $cities = [];
        return view('webui::Pages.coming-soon', compact('css', 'js'));
    }

}
