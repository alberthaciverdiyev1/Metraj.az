<?php

namespace Modules\WebUI\Http\Controllers\Base;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function features()
    {
        return get_data('/feature', [], false, true);
    }

    public function cities()
    {
        return get_data('/city', [], false, true);
    }

    public function propertyTypes()
    {
        return get_data('/property-types', [], true, true);
    }

    public function repairTypes()
    {
        return get_data('/repair-types', [], true, true);
    }

    public function roomCount(){
        return get_data('/room-count', [], true, true);
    }
    public function subways(){
        return get_data('/subway', [], false, true);
    }
}
