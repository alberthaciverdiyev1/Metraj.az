<?php

namespace Modules\WebUI\Http\Livewire;

use Livewire\Component;
use Modules\WebUI\Helpers\API;

class BrandSelect extends Component
{

    public $brands,$type;

    public function mount($type = null)
    {
        $this->brands = cache()->rememberForever('brands', function () {
            return API::call('api/brands');
        });
        $this->type = $type;

    }

    public function render()
    {
        return view('webui::livewire.brand-select');
    }
}
