# laravel-smsto
Laravel 5 package for sending SMS via SMS.to REST API

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
    use Intergo\SmsTo\SmsTo;
    
    class SmsController extends Controller
    {
      // Sending single SMS to one number
        public function send(SmsTo $sms)
        {
            $messages = [['to' => '+63917*******', 'message' => 'Hi Market!']];
            return $sms->setMessages($messages)
                       ->setSenderId('COLTD')
                       ->setCallbackUrl('https://mysite.org/smscallback')
                       ->sendSingle();
        }

        // Sending single SMS to multiple numbers
        public function broadcast(SmsTo $sms)
        {
            $message = 'Hi Market!';
            $recipients = ['+63917********', '+63919********'];
            return $sms->setMessage($message)
                       ->setRecipients($recipients)
                       ->setSenderId('COLTD')
                       ->setCallbackUrl('https://mysite.org/smscallback')
                       ->sendMultiple();
        }

        // Sending single SMS to a list of numbers
        public function sendList(SmsTo $sms)
        {
            $message = 'Hi Market!';
            return $sms->setMessage($message)
                       ->setListId(109)
                       ->setSenderId('COLTD')
                       ->setCallbackUrl('https://mysite.org/smscallback')
                       ->sendList();
        }
    }
```

You can also use the helper for sending SMS

```php
    $response = smsto()->setMessages([['to' => '+63917*******', 'message' => 'test message']])->sendSingle();
    // Success response
    [
      "success" => true,
      "message" => "Messages are queued for sending. Please check message logs on dashboard",
    ]
    // Error Response
    [
      "error" => true,
      "message" => "Messages not found",
    ]
```

```php
<html>
      <body>
         <form method='POST'>
            @if($errors->any())
            <ul>
            @foreach($errors->all() as $error)
            <li><strong>{{ $error }}</strong></li>
            @endforeach
            <ul>
            @endif
        @if(session('success'))
            <strong>{{ session('success') }}</strong>
        @endif
            <label for="to">Comma Separated Numbers</label>
            <input type='text' name='to' />
            <br>
            <label>Message/Body</label>
            <textarea name='messages'></textarea>
            <br><br>
            <button type='submit'>Send</button>
            @csrf
      </form>
    </body>
</html>
```

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.