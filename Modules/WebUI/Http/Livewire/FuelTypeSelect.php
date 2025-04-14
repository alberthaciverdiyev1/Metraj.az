<?php

namespace Modules\WebUI\Http\Livewire;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Modules\WebUI\Helpers\API;

class FuelTypeSelect extends Component
{
    public $fuel_types,$type;

    public function mount($type = null)
    {
        $this->fuel_types = Cache::rememberForever('fuel_types', function () {
            return API::call('api/fuel', 'GET', true);
        });
        $this->type = $type;
    }


    public function render()
    {
        return view('webui::livewire.fuel-type-select');
    }
}
