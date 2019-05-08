<?php 

namespace Intergo\SmsTo;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Facades\Blade;
//use Intergo\SmsTo\SmsTo;
use Carbon\Carbon;
use Storage;
use Intergo\SmsTo\Http\Client as SmsToClient;

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
        ], 'config');

        $this->publishes([
            __DIR__.'/../../views' => resource_path('views/vendor/smsto'),
        ], 'views');

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/smsto.php', 'smsto'
        );

        $this->loadViewsFrom(__DIR__.'/../../views', 'smsto');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind('laravel-smsto', function() {
        //     return new SmsTo();
        // });

        // If PHP SDK is ready
        $this->app->bind('laravel-smsto', function() {
            $accessToken = $this->getAccessToken();
            return new SmsToClient(
                config('smsto.client_id'),
                config('smsto.client_secret'),
                config('smsto.username'),
                config('smsto.password'),
                $accessToken
            );
        });
    }

    public function getAccessToken()
    {
        $accessToken = null;
        
        // Check if we have accessToken saved already
        if (!Storage::disk('local')->exists('smsto/accessToken')) {
            $client = new SmsToClient(
                config('smsto.client_id'),
                config('smsto.client_secret'),
                config('smsto.username'),
                config('smsto.password')
            );
            $response = $client->getAccessToken();

            if ($response) {
                if (isset($response['access_token'])) {
                    $date = Carbon::now()->addSeconds($response['expires_in']);
                    Storage::disk('local')->put('smsto/accessTokenExpiredOn', $date->toDateTimeString());
                    Storage::disk('local')->put('smsto/accessToken', $response['access_token']);
                    $accessToken = $response['access_token'];
                }
            }
        } else {
            $dateExpired = Storage::disk('local')->get('smsto/accessTokenExpiredOn');
            if ($dateExpired > Carbon::now()->toDateTimeString()) {
                $accessToken = Storage::disk('local')->get('smsto/accessToken');
            }
        }

        return $accessToken;
    }
}
