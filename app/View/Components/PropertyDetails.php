<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PropertyDetails extends Component
{
    public $details;
    public $extra;
    
    public function __construct($details, $extra)
    {
        $this->details = $details;
        $this->extra = $extra;
    }

    public function render()
    {
        return view('components.property-details');
    }
}