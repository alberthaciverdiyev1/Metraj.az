<?php

namespace Modules\WebUI\Http\Livewire;

use Livewire\Component;
use Modules\WebUI\Helpers\API;

class CitySelect extends Component
{
    public $cities;

    public function mount()
    {
        $this->cities = cache()->rememberForever('cities', function () {
            return API::call('api/cities');
        });
    }

    public function render()
    {
        return view('webui::livewire.city-select');
    }
}
