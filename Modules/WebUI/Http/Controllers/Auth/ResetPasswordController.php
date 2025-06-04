<?php

namespace Modules\WebUI\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function resetpassword()
    {
        $css = ['reset.css', 'app.css'];
        $js = [''];
        $cities = [];

        return view('webui::Auth.reset-password', compact('css', 'js'));
    }
}
