<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use Auth;
use Socialite;
use App\User;

class AuthController extends Controller
{
	public function __construct()
	{
		$this->client = DB::table('oauth_clients')
						  ->where('id', 2)
						  ->first();
	}

	// Some methods which were generated with the app
	/**
	 * Redirect the user to the OAuth Provider.
	 *
	 * @return Response
	 */
	public function redirectToProvider( $provider ) {

		return Socialite::driver( $provider )->redirect("https://google.com");
	}

	/**
	 * Obtain the user information from provider.  Check if the user already exists in our
	 * database by looking up their provider_id in the database.
	 * If the user exists, log them in. Otherwise, create a new user then log them in. After that
	 * redirect them to the authenticated users homepage.
	 *
	 * @return Response
	 */
	public function handleProviderCallback( $provider ) {

		$user     = Socialite::driver( $provider )->user();
		$authUser = $this->findOrCreateUser( $user, $provider );
		Auth::login($authUser, true);
		// Creating a token without scopes...
		$token = $authUser->createToken('Token Name')->accessToken;
		header("Location: " . env('FRONTEND_URL') ."/main/templates/create?type=iframe&code=" . $token);
		exit();
	}

	public function getUserData() {

	}


	/**
	 * If a user has registered before using social auth, return the user
	 * else, create a new user object.
	 *
	 * @param  $user Socialite user object
	 * @param $provider Social auth provider
	 *
	 * @return  User
	 */
	public function findOrCreateUser( $user, $provider ) {
		if ( $provider == 'staffconnect' ) {

            $authUser = User::where( 'provider_id',$user->provider_id )->first();
            
			if ( $authUser ) {
				return $authUser;
			}

			$newUser = User::create( [
				'first_name' => $user->first_name,
				'last_name' => $user->last_name,
				'email' => $user->email,
				'provider_name' => 'staffconnect',
                'provider_id' => $user->provider_id,
                'provider_company' => 'sc_demo',
				'password' => str_random( 8 ),
				'role' => 'staff',
				'active' => 'active',
            ] );
            
            return $newUser;
            
		} else if ( $provider == 'twitter' ) {
			// Empty for now
		}

	}
}
