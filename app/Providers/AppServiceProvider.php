<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BadChoice\Handesk\Handesk;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		Handesk::setup(config('services.handesk.url'), config('services.handesk.token'));
	}
}
