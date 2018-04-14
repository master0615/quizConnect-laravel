<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootStaffConnectSocialite();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    
	private function bootStaffConnectSocialite()
	{

		$socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
		$socialite->extend(
			'staffconnect',
			function ($app) use ($socialite) {
				$config = $app['config']['services.staffconnect'];
				return $socialite->buildProvider(StaffProvider::class, $config);
			}
		);
	}
}
