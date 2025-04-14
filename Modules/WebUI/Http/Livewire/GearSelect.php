<?php

namespace Modules\WebUI\Http\Livewire;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Modules\WebUI\Helpers\API;

class GearSelect extends Component
{
    public $gears,$type;

    public function mount($type = null)
    {
        $this->gears = Cache::rememberForever('gears', function () {
            return API::call('api/gears', 'GET', true);
        });
        $this->type = $type;
    }


    public function render()
    {
        return view('webui::livewire.gear-select');
    }
}
