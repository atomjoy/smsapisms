<?php

namespace Atomjoy\Sms;

use Atomjoy\Sms\Providers\SmsChannelProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;

class SmsServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'smsapisms');
		$this->app->register(SmsChannelProvider::class);
	}

	public function boot(Kernel $kernel)
	{
		if ($this->app->runningInConsole()) {
			$this->publishes([
				__DIR__ . '/../config/config.php' => config_path('smsapisms.php'),
			], 'apilogin-config');
		}
	}
}
