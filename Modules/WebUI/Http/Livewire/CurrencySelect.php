<?php

namespace Modules\WebUI\Http\Livewire;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Modules\WebUI\Helpers\API;

class CurrencySelect extends Component
{
    public $currencies,$type;

    public function mount($type = null)
    {
        $this->currencies = Cache::rememberForever('currencies', function () {
            return  API::call('api/currencies','GET',true);
        });
        $this->type = $type;

    }

    public function render()
    {
        return view('webui::livewire.currency-select');
    }
}
