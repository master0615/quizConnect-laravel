<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'facebook' => [
		'client_id' => '205741590183865',
		'client_secret' => 'ee7879943ce719a7949a3ecc732dafd9',
		'redirect' => env( 'APP_URL' ) . '/api/auth/facebook/callback',
	],


	'twitter' => [
		'client_id' => 'v9FCqL3asvHnavNLa2MY1Atug',
		'client_secret' => 'dwFVaR4s4I5myp7WE2a9Z1m1uSA8ITFrb6UZcgfqhX8AMfTF99',
		'redirect' => env( 'APP_URL' ) . '/api/auth/twitter/callback',
	],

	// Dev environment
	'staffconnect' => [
		'client_id' => env("OAUTH_ID"),
		'client_secret' => env("OAUTH_SECRET"),
		'redirect' => env("OAUTH_CALLBACK"),
	],

];
