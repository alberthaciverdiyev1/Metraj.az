<?php

namespace Modules\WebUI\Http\Livewire;

use Livewire\Component;
use Modules\WebUI\Helpers\API;

class BrandSelect extends Component
{

    public $brands;

    public function mount()
    {
        $this->brands = cache()->rememberForever('brands', function () {
            return API::call('api/brands');
        });
    }

    public function render()
    {
        return view('webui::livewire.brand-select');
    }
}
