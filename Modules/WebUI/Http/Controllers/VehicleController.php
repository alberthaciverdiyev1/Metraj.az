<?php

namespace Modules\WebUI\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\WebUI\Helpers\API;

class VehicleController extends Controller
{

    public function index(){

        $brands = API::call('api/brand');
        $cities = API::call('api/cities');
        $currencies = API::call('api/currencies','GET',true);
        $fuel_types = API::call('api/fuel','GET',true);
        $body_types = API::call('api/body','GET',true);
        $gears = API::call('api/gears','GET',true);
        $gearbox = API::call('api/gearbox','GET',true);

        return view('webui::vehicles.add',compact('brands','cities','currencies','fuel_types','body_types','gears','gearbox'));
    }

}
