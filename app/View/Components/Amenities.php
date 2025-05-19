<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Amenities extends Component
{
    public $amenities;
    public $columns;
    
    public function __construct($amenities, $columns = 3)
    {
        $this->amenities = $amenities;
        $this->columns = $columns;
    }

    public function render()
    {
        return view('components.amenities');
    }
}