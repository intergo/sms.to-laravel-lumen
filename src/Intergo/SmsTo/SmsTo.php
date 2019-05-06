<?php 

namespace Intergo\SmsTo;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

class SmsTo {
    
    protected $client;
    public $accessToken;
    public $message;
    public $messages;
    public $senderId;
    public $recipients;
    public $listId;
    public $callbackUrl;
    public $baseUrl = 'https://api.smsto.space/v1';

    public function __construct()
    {
        
    }

    public function getAccessToken()
    {
        $authUrl = $this->baseUrl . '/oauth/token';
        
        // Change all credenatials
        $this->credentials = [
            'grant_type' => 'password',
            'client_id' => config('smsto.client_id'),
            'client_secret' => config('smsto.client_secret'),
            'username' => config('smsto.username'),
            'password' => config('smsto.password'),
            'scope' => '*'
        ];

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $client = new Client(['headers' => $headers, 'verify' => false]);
        
        $response = $client->post($authUrl, [
                        'json' => $this->credentials,
                    ])->getBody()->getContents();
        $response = json_decode($response);

        $this->accessToken = $response->access_token;

        return $this->accessToken;
    }

    public function refreshToken()
    {
        $authUrl = $this->baseUrl . '/oauth/token';
        
        // Change all credenatials
        $this->credentials = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->accessToken,
            'client_id' => config('smsto.client_id'),
            'client_secret' => config('smsto.client_secret'),
            'scope' => ''
        ];

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $client = new Client(['headers' => $headers, 'verify' => false]);
        
        $response = $client->post($authUrl, [
                        'json' => $this->credentials,
                    ])->getBody()->getContents();
        $response = json_decode($response);

        $this->accessToken = $response->access_token;
    }

    public function sendSingle()
    {
        $this->getAccessToken();
        
        $path = $this->baseUrl . '/sms/single/send';

        $body = [
            'messages' => $this->messages,
            'send_id' => $this->senderId,
            'callback_url' => $this->callbackUrl
        ];
        return $this->request($path, 'post', $body);
    }

    public function sendMultiple()
    {
        $this->getAccessToken();
        
        $path = $this->baseUrl . '/sms/send';
        
        $body = [
            'body' => $this->message,
            'to' => $this->recipients,
            'send_id' => $this->senderId,
            'callback_url' => $this->callbackUrl
        ];
        return $this->request($path, 'post', $body);
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function setMessages($messages)
    {
        $this->messages = $messages;
        return $this;
    }

    public function setSenderId($senderId)
    {
        $this->senderId = $senderId;
        return $this;
    }

    public function setRecipients($recipients)
    {
        $this->recipients = $recipients;
        return $this;
    }

    public function setListId($listId)
    {
        $this->listId = $listId;
        return $this;
    }

    public function setCallbackUrl($callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;
        return $this;
    }

    public function request($path, $method, $data = [])
    {
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $headers['Authorization'] = ' Bearer ' . $this->accessToken;

        $client = new Client(['headers' => $headers, 'verify' => false]);
        $response = '';
        try
        {
            switch ($method)
            {
                case 'get':
                    $response = $client->get($path)->getBody()->getContents();
                    break;
                case 'post':
                    $response = $client->post($path, [
                        'json' => $data,
                    ])->getBody()->getContents();
                    break;
                case 'delete':
                    $response = $client->delete($path)->getBody()->getContents();
                    break;
                case 'put':
                    $response = $client->put($path, [
                        'json' => $data,
                    ])->getBody()->getContents();
                    break;
                default:
                    $response = '';
                    break;
            }
            $response = json_decode($response, true);

        } catch (Exception $e) {
            
        } catch (RequestException $e) {
            $response = $this->exception($e);
        } catch (ClientException $e) {
            $response = $this->exception($e);
        } catch (ServerException $e) {
            $response = $this->exception($e);
        }

        return $response;
    }

    public function exception($e)
    {
        if ($e->hasResponse()) {
            $response = $e->getResponse();
            return json_decode($response->getBody()->getContents(), true);
        }

        return false;
    }
}