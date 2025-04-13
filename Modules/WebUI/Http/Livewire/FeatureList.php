<?php

namespace Modules\WebUI\Http\Livewire;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Modules\Feature\Http\Entities\Feature;
use Modules\WebUI\Helpers\API;

class FeatureList extends Component
{
    public $features;

    public function mount(){
        $this->features = Cache::rememberForever('features', function () {
            return API::call('api/features');
        });
    }

    public function render()
    {
        return view('webui::livewire.feature-list');
    }
}
