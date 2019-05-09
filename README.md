# laravel-smsto
Laravel 5 package for sending SMS via SMS.to REST API

## Installation

You need to have a working account on Sms.To with sufficient balance loaded to be able to use this package. If you do not have one, [please get one here](https://sms.to).

Require this package with composer.

```shell
composer require intergo/laravel-smsto
```

You might need to add `Intergo\SmsTo\ServiceProvider::class`, to the providers array `config/app.php` if your laravel version is less than 5.5.

## Configuration

Publish the configuration file

```shell
php artisan vendor:publish --provider="Intergo\SmsTo\ServiceProvider" --tag=config
```

You will get a config file named `smsto.php` in your config directory. Customise the defaults to your smsto configurations.


```php
<?php

  return [
      'grant_type' => 'password',
      'client_id' => env('SMSTO_CLIENT_ID'),
      'client_secret' => env('SMSTO_CLIENT_SECRET'),
      'username'=> env('SMSTO_EMAIL'),
      'password' => env('SMSTO_PASSWORD'),
      'scope' => '*',
      'sender_id' => env('SMSTO_SENDER_ID'),
      'callback_url' => env('SMSTO_CALLBACK_URL'),
  ];
```

If you want to use the existing view file then run command
```shell
php artisan vendor:publish --provider="Intergo\SmsTo\ServiceProvider" --tag=views
```

It will generate view files located in `resources/views/vendor/smsto`

## Usage

Change or add configuration in you `.env` file

```shell
SMSTO_CLIENT_ID=xyxy1234
SMSTO_CLIENT_SECRET=xyz1234567
SMSTO_EMAIL=email@sms.to
SMSTO_PASSWORD=y2kP@szword
SMSTO_SENDER_ID=smsto
SMSTO_CALLBACK_URL=https://mysite.org/smscallback
```

Create your route

```php
    Route::post('/sms/send', 'SmsController@send');
    Route::post('/sms/broadcast', 'SmsController@broadcast');
```

```php
    <?php
    
    namespace App\Http\Controllers;
    
    use Illuminate\Http\Request;
    use SmsTo;
    
    class SmsController extends Controller
    {
      // Sending single SMS to one number
        public function send()
        {
            $messages = [['to' => '+63917*******', 'message' => 'Hi Market!']];
            return SmsTo::setMessages($messages)
                       ->setSenderId('COLTD')
                       ->setCallbackUrl('https://mysite.org/smscallback')
                       ->sendSingle();
        }

        // Sending single SMS to multiple numbers
        public function broadcast()
        {
            $message = 'Hi Market!';
            $recipients = ['+63917********', '+63919********'];
            return SmsTo::setMessage($message)
                       ->setRecipients($recipients)
                       ->setSenderId('COLTD')
                       ->setCallbackUrl('https://mysite.org/smscallback')
                       ->sendMultiple();
        }

        // Sending single SMS to a list of numbers
        public function sendList()
        {
            $message = 'Hi Market!';
            return SmsTo::setMessage($message)
                       ->setListId(109)
                       ->setSenderId('COLTD')
                       ->setCallbackUrl('https://mysite.org/smscallback')
                       ->sendList();
        }
    }
```

## Lumen

Require this package with composer.

```shell
composer require intergo/laravel-smsto
```

Uncomment the following lines in the bootstrap file:
```php
// bootstrap/app.php:
$app->withFacades();
$app->withEloquent();

// Add SmsTo Facade
if (!class_exists('SmsTo')) {
    class_alias('Intergo\SmsTo\Facades\SmsToFacade', 'SmsTo');
}
```
Configure the service provider (and AppServiceProvider if not already enabled):

```php
// bootstrap/app.php:
$app->register(App\Providers\AppServiceProvider::class);
$app->register(Intergo\SmsTo\ServiceProvider::class);
```

Update the AppServiceProvider register method to bind the filesystem manager to the IOC container:

```php
// app/Providers/AppServiceProvider.php
public function register()
{
    $this->app->singleton('filesystem', function ($app) {
        return $app->loadComponent('filesystems', 'Illuminate\Filesystem\FilesystemServiceProvider', 'filesystem');
    });

    $this->app->bind('Illuminate\Contracts\Filesystem\Factory', function($app) {
        return new \Illuminate\Filesystem\FilesystemManager($app);
    });
}
```

Manually copy the package config file to `app\config\smsto.php` (you may need to create the config directory if it does not already exist).

Copy the Laravel filesystem config file into `app\config\filesystem.php`. You should add a disk configuration to the filesystem config matching the config file.

Finally, update boostrap/app.php to load both config files:

```php
// bootstrap/app.php
$app->configure('smsto');
$app->configure('filesystems');
```

## Send SMS using Lumen

```php
// routes/web.php
$router->post('send', function () {
    $messages = [['to' => '+63917*******', 'message' => 'Hi Market!']];
    return \SmsTo::setMessages($messages)
               ->setSenderId('COLTD')
               ->setCallbackUrl('https://mysite.org/smscallback')
               ->sendSingle();
});
```

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.