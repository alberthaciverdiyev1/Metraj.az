<?php
namespace App\View\Components;

use Illuminate\View\Component;

class PropertyCard extends Component
{
    public $image, $title, $address, $beds, $baths, $area, $price;

    public function __construct($image, $title, $address, $beds, $baths, $area, $price)
    {
        $this->image = $image;
        $this->title = $title;
        $this->address = $address;
        $this->beds = $beds;
        $this->baths = $baths;
        $this->area = $area;
        $this->price = $price;
    }

    public function render()
    {
        return view('components.property-card');
    }
}
