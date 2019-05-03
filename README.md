# laravel-smsto
Laravel 5 package for sending sms in SMS.to


## Installation

You need to be have an Sms.To account to use this package. If you do not have one, [get here](https://sms.to).

Require this package with composer.

```shell
composer require intergo/laravel-smsto
```

You might need to add `Intergo\SmsTo\ServiceProvider::class`, to the providers array `config/app.php` if your laravel version is less than 5.5.

## Configuration

Publish the configuration file

```shell
php artisan vendor:publish --provider="Intergo\SmsTo\ServiceProvider"
```

You will get a config file named `smsto.php` in your config directory. Customize the defaults to your smsto configurations.


```php
<?php

	return [
	    'grant_type' => 'password',
	    'client_id' => env('SMSTO_CLIENT_ID'),
	    'client_secret' => env('SMSTO_CLIENT_SECRET'),
	    'username'=> env('SMSTO_EMAIL'),
	    'password' => env('SMSTO_PASSWORD'),
	    'scope' => '*',
	    'callback_url' => env('SMSTO_CALLBACK_URL'),
	];
```


## Usage

Change or add configuration in you `.env` file

```shell
SMSTO_CLIENT_ID=xyxy
SMSTO_CLIENT_SECRET=xyz
SMSTO_EMAIL=email@example.com
SMSTO_PASSWORD=y2kP@szword
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
    use Intergo\SmsTo\SmsTo;
    
    class SmsController extends Controller
    {
    	// Sending single SMS to one number
        public function send(SmsTo $smsTo)
        {
            $message = 'Hi Market!';
            $recipients = '+63917********';
            return $sms->setMessage($message)
                       ->setRecipients($recipients)
                       ->setSenderId('COLTD')
                       ->setCallbackUrl('https://mysite.org/smscallback')
                       ->send();
        }

        // Sending single SMS to multiple numbers
        public function broadcast(SmsTo $smsTo)
        {
            $message = 'Hi Market!';
            $recipients = ['+63917********', '+63919********'];
            return $sms->setMessage($message)
                       ->setRecipients($recipients)
                       ->setSenderId('COLTD')
                       ->setCallbackUrl('https://mysite.org/smscallback')
                       ->send();
        }
    }
```


## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.