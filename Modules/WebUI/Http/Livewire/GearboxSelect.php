<?php

namespace Modules\WebUI\Http\Livewire;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Modules\WebUI\Helpers\API;

class GearboxSelect extends Component
{
    public $gearbox,$type;

    public function mount($type = null)
    {
        $this->gearbox = Cache::rememberForever('gearbox', function () {
            return API::call('api/gearbox', 'GET', true);
        });
        $this->type = $type;
    }

    public function render()
    {
        return view('webui::livewire.gearbox-select');
    }
}
