<?php

namespace Modules\WebUI\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('POST')) {
            $credentials = $request->only('email', 'password');

            $response = post_data('/login', $credentials);
            if (isset($response['token'])) {
                session([
                    'jwt_token' => $response['token'],
                    'user' => $response['user'],
                ]);

                return json_encode([
                    'status' => 201,
                    'message' => __('Login successfully!'),
                    'route' => route('home')
                ]);
            } else {
                return $response;
            }
        } else {
            $css = ['registerlogin.css', 'app.css'];
            $js = [
                'gotop.js',
                'auth/login.js',
            ];
            return view('webui::Auth.login', compact('css', 'js'));
        }
    }
}
