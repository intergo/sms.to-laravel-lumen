<p align="center">
  <a href="https://sms.to"><img width="400" src="https://sms.to/images/logo.svg"></a>
</p>

<h2 align="center">
API integration for Laravel 
</h2>

This package allows you to easily intergate [SMS.to API](https://sms.to/api-docs) into Laravel and Lumen projects.

[SMS.to](https://sms.to) is a bulk SMS marketing platform that offers a smarter way for businesses to communicate with customers through multiple channels. Reach customers in their preferred channel through SMS, WhatsApp or Viber Messages. For more details, please visit: https://sms.to

## Requirements

This package requires Laravel 5.2 or higher and you need to have a working [SMS.to](https://sms.to) account with sufficient balance. 

## Installation

You can install this package into Laravel project via Composer:

```shell
composer require intergo/laravel-smsto
```

If you are using Laravel 5.5 or above, the package will automatically register the `SmsTo` provider.
If you are using Laravel 5.4 or below, add `Intergo\SmsTo\ServiceProvider::class` to the `providers` array and `'SmsTo' => Intergo\SmsTo\Facades\SmsToFacade::class` in the `aliases` array in  `config/app.php`:

```php
'providers' => [
    // Other service providers...
    Intergo\SmsTo\ServiceProvider::class,
],
'aliases' => [
    'SmsTo' => Intergo\SmsTo\Facades\SmsToFacade::class,
],
```
The instructions for installing this package into Lumen project are given [below](#lumen).

## Configuration

Publish the configuration file:

```shell
php artisan vendor:publish --provider="Intergo\SmsTo\ServiceProvider" --tag=config
```
Then set the values for the parameters in `config/smsto.php`. Credentials (`client_id`, `client_secret`, `username` and `password`) are required. Optionally, you can set your sender ID (`sender_id`) and callback URL (`callback_url`).

It is recommended that you set all these configuration values in your `.env` file:

```shell
#REQUIRED:
SMSTO_CLIENT_ID=
SMSTO_CLIENT_SECRET=
SMSTO_EMAIL=
SMSTO_PASSWORD=
#OPTIONAL:
SMSTO_SENDER_ID=
SMSTO_CALLBACK_URL=
```

## Usage

After the package is installed and configured, the only thing you have to do is to use the `SmsTo` facade:

```php
use SmsTo;
```
in class files (typically controllers) in which you will use this package for sending SMS.

### Sending SMS to multiple numbers (broadcasting):
```php
// Text message that will be sent to multiple numbers:
$message = 'Hello World!';

// Array of mobile phone numbers (starting with the "+" sign and country code):
$recipients = ['+4474*******', '+35799******', '+38164*******'];

// Send (broadcast) the $message to $recipients: 
SmsTo::setMessage($message)
    ->setRecipients($recipients)
    ->sendMultiple();
```
As for the sender ID and callback URL, the values set in the configuration file will be used by default. You can also specify these values by using the `->setSenderId()` and `->setCallbackUrl()` methods:
```php
SmsTo::setMessage($message)
    ->setRecipients($recipients)
    ->setSenderId('YOUR_NAME')
    ->setCallbackUrl('https://your-site.com/smscallback')
    ->sendMultiple();
```
Please note that using these methods will override the values set in the configuration file.


### Sending different SMS to single numbers:

```php
 $messages = [
    [
        'to' => '+4474*******',
        'message' => 'Hello World!'
    ],
    [
        'to' => '+35799******',
        'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'
    ],
];

SmsTo::setMessages($messages)->sendSingle();
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
