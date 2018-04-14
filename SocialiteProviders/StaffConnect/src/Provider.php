<?php

namespace SocialiteProviders\StaffConnect;

use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;
use Illuminate\Http\Request;

class StaffProvider extends AbstractProvider implements ProviderInterface
{
	/**
	 * Unique Provider Identifier.
	 */
	const IDENTIFIER = 'STAFFCONNECT';

	/**
	 * StaffConnect endpoint
	 *
	 * @var string
	 */
	protected $apiUrl = 'https://api.demo.staffconnect-app.com';



	/**
	 * The scopes being requested
	 *
	 * @var array
	 */

	/**
	 * Get the authentication URL for the provider.
	 *
	 * @param  string $state
	 * @return string
	 */
	protected function getAuthUrl($state)
	{
		return $this->buildAuthUrlFromBase($this->apiUrl . '/oauth/authorize', $state);
	}

	/**
	 * Get the token URL for the provider.
	 *
	 * @return string
	 */
	protected function getTokenUrl()
	{
		return $this->apiUrl . '/oauth/token';
	}

	protected function getTokenFields($code)
	{
		return array_add(
			parent::getTokenFields($code), 'grant_type', 'authorization_code'
		);
	}

	/**
	 * Get the raw user for the given access token.
	 *
	 * @param  string $token
	 * @return array
	 */
	protected function getUserByToken($token)
	{
		$response = $this->getHttpClient()->get($this->apiUrl . '/api/oauth/user', [
			'headers' => [
				'Authorization' => 'Bearer ' . $token,
			],
		]);
		return json_decode($response->getBody(), true);
	}

	/**
	 * Map the raw user array to a Socialite User instance.
	 *
	 * @param  array $user
	 * @return \Laravel\Socialite\User
	 */
	protected function mapUserToObject(array $user)
	{
		return (new User)->setRaw($user)->map([
			'provider_id' => $user['id'],
			'first_name' => $user['fname'],
			'last_name' => $user['lname'],
			'email'     =>  $user['email'],
			'provider_name'     =>  'staffconnect',
			'provider_company'  =>  'sc_demo'
		]);
	}
}