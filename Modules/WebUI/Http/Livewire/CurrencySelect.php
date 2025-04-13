<?php

namespace Modules\WebUI\Http\Livewire;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Modules\WebUI\Helpers\API;

class CurrencySelect extends Component
{
    public $currencies;

    public function mount()
    {
        $this->currencies = Cache::rememberForever('currencies', function () {
            return  API::call('api/currencies','GET',true);
        });
    }

    public function render()
    {
        return view('webui::livewire.currency-select');
    }
}
