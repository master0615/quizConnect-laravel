<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    private $client;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = DB::table('oauth_clients')
            ->where('id', 2)
            ->first();
    }

    /**
     * /auth/login
     */
    protected function login(Request $request)
    {
        $request->request->add([
            'username' => $request->username,
            'password' => $request->password,
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'scope' => '*',
        ]);



        $proxy = Request::create('oauth/token', 'POST');
        $response = Route::dispatch($proxy);

        if (!$response->isSuccessful()) {
            throw new \App\Exceptions\InvalidUserException();
        }

        $data = json_decode($response->getContent());

        $data->user = User::select('id', 'first_name', 'last_name', 'role', 'active', 'provider_name', 'provider_id')->where('email', $request->username)->firstOrFail();

        if ($data->user->active == 'inactive') {
            throw new \App\Exceptions\InactiveUserException();
        }

        if ($data->user->active == 'block') {
            throw new BlockUserException();
        }

        return response()->json($data, 200);
    }

    /**
     * /auth/refresh
     */
    protected function refreshToken(Request $request)
    {
        $request->request->add([
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->refresh_token,
            'client_id' => $this->client->id,//env('PASSWORD_CLIENT_ID'),
            'client_secret' => $this->client->secret,//env('PASSWORD_CLIENT_SECRET'),
        ]);

        $proxy = Request::create('/oauth/token', 'POST');

        return Route::dispatch($proxy);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('api');
    }

    /**
     * /auth/logout
     */
    public function logout(Request $request)
    {
        if (!$this->guard()->check()) {
            return response()->json([
                'message' => "No active user session was found.",
            ], 404);
        }

        // Taken from: https://laracasts.com/discuss/channels/laravel/laravel-53-passport-password-grant-logout
        $request->user('api')
            ->token()
            ->revoke();

        Auth::guard()->logout();

        Session::flush();

        Session::regenerate();

        return response()->json([
            'message' => "Logout!",
        ]);
    }    
}
