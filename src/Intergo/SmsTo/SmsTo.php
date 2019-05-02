<?php 

namespace Intergo\SmsTo;

use GuzzleHttp\Client;

class SmsTo {
    
    public $accessToken;
    protected $client;

    public $message;
    public $senderId;
    public $recipients;
    public $callbackUrl;

    public function __construct()
    {
        $this->client = new Client;
    }

    public function auth()
    {

    }

    public function send($request)
    {
        
    }


    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setSenderId($senderId)
    {
        $this->senderId = $senderId;
    }

    public function setRecipients($recipients)
    {
        $this->recipients = $recipients;
    }
}