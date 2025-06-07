<?php

namespace Modules\WebUI\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        if ($request->isMethod('POST')) {
            $credentials = $request->only('name', 'email', 'password', 'password_confirmation');

            $response = post_data('/register', $credentials);
            if (isset($response['token'])) {
                session([
                    'jwt_token' => $response['token'],
                    'user' => $response['user'],
                ]);

                return json_encode([
                    'status' => 201,
                    'message' => __('Registered successfully!'),
                    'route' => route('home')
                ]);
            } else {
                return $response;
            }
        } else {
            $css = ['registerlogin.css', 'app.css'];
            $js = [
                'gotop.js',
                'auth/register.js',
            ];
            return view('webui::Auth.register', compact('css', 'js'));
        }
    }

}
