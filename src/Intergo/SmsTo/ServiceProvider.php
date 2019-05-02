<?php 

namespace Intergo\SmsTo;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Intergo\SmsTo\SmsTo;

class ServiceProvider extends BaseServiceProvider {
    
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/smsto.php' => config_path('smsto.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/smsto.php', 'smsto'
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('laravel-smsto', function() {
            return new SmsTo();
        });
    }

}
