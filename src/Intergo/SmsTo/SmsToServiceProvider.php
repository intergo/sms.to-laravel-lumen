<?php

namespace Intergo\SmsTo;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Intergo\SmsTo\Credentials\ApiKeyCredential;
use Intergo\SmsTo\Credentials\ICredential;
use Intergo\SmsTo\Credentials\OauthCredential;
use Intergo\SmsTo\Module\Auth\Credential;
use Intergo\SmsTo\Module\BaseModule;
use Intergo\SmsTo\Module\Contact\Contact;
use Intergo\SmsTo\Module\NumberLookup\NumberLookup;
use Intergo\SmsTo\Module\Shortlink\Shortlink;
use Intergo\SmsTo\Module\Sms\Sms;
use Intergo\SmsTo\Module\Team\Team;

class SmsToServiceProvider extends BaseServiceProvider
{

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
        $this->app->bind('smsto-auth', function () {
            $authType = config('smsto.auth_mode');
            if ($authType === 'api_key') {
                return new Credential(new ApiKeyCredential(config('smsto.api_key')));
            }
            $clientID = config('smsto.client_id');
            $secret = config('smsto.secret');
            $expiresIn = config('smsto.token_expire_ttl');
            $enableAutoRefreshToken = config('smsto.enable_token_auto_refresh');
            $autoRefreshOffset = config('smsto.auto_refresh_offset');
            return new Credential(new OauthCredential($clientID, $secret, $expiresIn, $enableAutoRefreshToken, $autoRefreshOffset));
        });

        $this->bindModule('smsto-sms', Sms::class, 'smsto.sms_url');
        $this->bindModule('smsto-contact', Contact::class, 'smsto.contact_url');
        $this->bindModule('smsto-team', Team::class, 'smsto.team_url');
        $this->bindModule('smsto-shortlink', Shortlink::class, 'smsto.shortlink_url');
        $this->bindModule('smsto-number-lookup', NumberLookup::class, 'smsto.number_lookup_url');

    }

    private function authVerify(): ICredential
    {
        $auth = $this->app->make('smsto-auth');
        $auth->setBaseUrl(config('smsto.auth_url'));
        return $auth->verify();
    }

    private function bindModule($abstract, $class, $baseUrl)
    {
        $this->app->bind($abstract, function () use ($class, $baseUrl) {
            $credentials = $this->authVerify();
            $object = new $class($credentials);
            $object->setBaseUrl(config($baseUrl));
            return $object;
        });
    }
}
