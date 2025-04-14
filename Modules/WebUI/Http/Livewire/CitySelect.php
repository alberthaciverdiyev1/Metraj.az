<?php

namespace Modules\WebUI\Http\Livewire;

use Livewire\Component;
use Modules\WebUI\Helpers\API;

class CitySelect extends Component
{
    public $cities,$type;
    public string $class;

    public function mount($type = null)
    {
        $this->cities = cache()->rememberForever('cities', function () {
            return API::call('api/cities');
        });
        $this->type = $type;
    }

    public function render()
    {
        return view('webui::livewire.city-select');
    }
}
