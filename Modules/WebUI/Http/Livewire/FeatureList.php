<?php

namespace Modules\WebUI\Http\Livewire;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Modules\Feature\Http\Entities\Feature;
use Modules\WebUI\Helpers\API;

class FeatureList extends Component
{
    public $features,$type;

    public function mount($type = null){
        $this->features = Cache::rememberForever('features', function () {
            return API::call('api/features');
        });
        $this->type = $type;
    }

    public function render()
    {
        return view('webui::livewire.feature-list');
    }
}
