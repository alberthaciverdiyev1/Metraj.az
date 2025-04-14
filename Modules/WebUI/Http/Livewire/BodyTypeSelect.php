<?php

namespace Modules\WebUI\Http\Livewire;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Modules\Base\Enums\BodyType;
use Modules\WebUI\Helpers\API;

class BodyTypeSelect extends Component
{

    public $body_types,$type;

    public function mount($type = null)
    {
        $this->body_types = Cache::rememberForever('body_types', function () {
            return API::call('api/body', 'GET', true);
        });
        $this->type = $type;
    }

    public function render()
    {
        return view('webui::livewire.body-type-select');
    }
}
