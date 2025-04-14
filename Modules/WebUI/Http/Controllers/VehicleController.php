<?php

namespace Modules\WebUI\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\WebUI\Helpers\API;

class VehicleController extends Controller
{

    public function index(){
        return view('webui::vehicles.add');
    }

}
