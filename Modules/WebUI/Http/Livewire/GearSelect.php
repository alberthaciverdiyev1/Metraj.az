<?php

namespace Modules\WebUI\Http\Livewire;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Modules\WebUI\Helpers\API;

class GearSelect extends Component
{
    public $gears;

    public function mount()
    {
        $this->gears = Cache::rememberForever('gears', function () {
            return API::call('api/gears', 'GET', true);
        });
    }


    public function render()
    {
        return view('webui::livewire.gear-select');
    }
}
