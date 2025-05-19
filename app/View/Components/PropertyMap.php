<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PropertyMap extends Component
{
    public $mapData;
    public $zoom;
    public $mapType;
    
    public function __construct($mapData, $zoom = 14, $mapType = 'roadmap')
    {
        $this->mapData = $mapData;
        $this->zoom = $zoom;
        $this->mapType = $mapType;
    }

    public function generateMapUrl()
    {
        $lat = $this->mapData['latitude'];
        $lng = $this->mapData['longitude'];
        
        return "https://www.google.com/maps/embed/v1/view?key=YOUR_API_KEY&center={$lat},{$lng}&zoom={$this->zoom}&maptype={$this->mapType}";
    }

    public function render()
    {
        return view('components.property-map');
    }
}