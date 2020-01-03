<?php

namespace PixelAdmin\Admin;

use Illuminate\Support\ServiceProvider;


class PixelAdminServiceProvider extends ServiceProvider{
	
	public function boot(){
		
		$this->loadRoutesFrom(__DIR__.'/routes/admin.php');
		$this->loadRoutesFrom(__DIR__.'/routes/json.php');
		
		$this->loadViewsFrom(__DIR__.'/views' , 'pixel-admin');
				
		$this->loadMigrationsFrom(__DIR__.'/database/migrations');
		
		
		$this->app['router']->aliasMiddleware('checkifmainadmin', \PixelAdmin\Admin\Middleware\CheckIfMainAdmin::class);
		
	}
	
	
	public function register(){
		
	}
}