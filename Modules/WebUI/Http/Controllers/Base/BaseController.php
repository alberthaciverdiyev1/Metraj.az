<?php

namespace Modules\WebUI\Http\Controllers\Base;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function features()
    {
        return get_data('/feature',[],false,true);
    }

    public function cities()
    {
        return get_data('/city',[],false,true);
    }
}
