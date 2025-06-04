<?php

namespace Modules\WebUI\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class OtpController extends Controller
{
  public function otp()
{
    $css = ['registerlogin.css', 'app.css', 'otp.css'];
    $js = ['otp.js'];
    $cities = [];

    return view('webui::Auth.otp', compact('css', 'js'));
}

}
