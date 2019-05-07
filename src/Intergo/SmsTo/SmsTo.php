<?php 

namespace Intergo\SmsTo;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SmsTo {
    
    /**
     * Guzzle Client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Access token.
     *
     * @var string
     */
    public $accessToken;

    /**
     * The message that will going to send.
     *
     * @var string
     */
    public $message;

    /**
     * Array of destination numbers with their respective personalized messages to be sent
     *
     * @var array
     */
    public $messages;

    /**
     * The sender ID which is optional
     *
     * @var string
     */
    public $senderId;

    /**
     * Array of phone numbers.
     *
     * @var array
     */
    public $recipients;

    /**
     * List id.
     *
     * @var int
     */
    public $listId;

    /**
     * Callback URL
     *
     * This will be an optional URL where we will POST some information 
     * about the status of SMS as soon as we have an update
     *
     * @var string
     */
    public $callbackUrl;

    /**
     * Base URL
     *
     * Base URL to be use for calling API
     * Example URL is https://api.sms.to/v1
     *
     * @var string
     */
    public $baseUrl;

    public function __construct()
    {
        $this->senderId = config('smsto.sender_id');
        $this->callbackUrl = config('smsto.callback_url');
        $this->baseUrl = config('smsto.base_url');
    }

    /**
     * Get Access token.
     *
     * @return string
     */
    public function getAccessToken()
    {
        $url = $this->baseUrl . '/oauth/token';
        
        // Change all credentials
        $this->credentials = [
            'grant_type' => 'password',
            'client_id' => config('smsto.client_id'),
            'client_secret' => config('smsto.client_secret'),
            'username' => config('smsto.username'),
            'password' => config('smsto.password'),
            'scope' => '*'
        ];

        return $this->token($url);
    }

    /**
     * Refresh Access token.
     *
     * @return string
     */
    public function refreshToken()
    {
        $url = $this->baseUrl . '/oauth/token';
        
        // Change all credenatials
        $this->credentials = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->accessToken,
            'client_id' => config('smsto.client_id'),
            'client_secret' => config('smsto.client_secret'),
            'scope' => ''
        ];

        return $this->token($url);
    }

    /**
     * Access token.
     *
     * @return string|bool
     */
    public function token($url)
    {
        // Check if we have accessToken saved already
        if (Storage::disk('local')->exists('smsto/accessToken')) {
            $dateExpired = Storage::disk('local')->get('smsto/accessTokenExpiredOn');
            if ($dateExpired > Carbon::now()->toDateTimeString()) {
                $this->accessToken = Storage::disk('local')->get('smsto/accessToken');
                return $this->accessToken;
            }
        }

        $response = $this->request($url, 'post', $this->credentials);

        if ($response) {
            $date = Carbon::now()->addSeconds($response['expires_in']);
            Storage::disk('local')->put('smsto/accessTokenExpiredOn', $date->toDateTimeString());
            Storage::disk('local')->put('smsto/accessToken', $response['access_token']);

            $this->accessToken = $response['access_token'];

            return $this->accessToken;
        }

        return false;
    }

    /**
     * Get user cash balance.
     *
     * @return array
     */
    public function getBalance()
    {
        $this->getAccessToken();
        
        $path = $this->baseUrl . '/balance';

        $body = [];

        return $this->request($path, 'post', $body);
    }

    /**
     * Sends personalized SMS to a single number or array of 
     * numbers with personalized SMS.
     *
     * @return array
     */
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

    /**
     * This will send a message to multiple numbers specified in request body.
     *
     * @return array
     */
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

    /**
     * Sending single SMS to a list.
     *
     * @return array
     */
    public function sendList()
    {
        $this->getAccessToken();
        $path = $this->baseUrl . '/sms/send';
        
        $body = [
            'body' => $this->message,
            'to_list_id' => $this->listId,
            'send_id' => $this->senderId,
            'callback_url' => $this->callbackUrl
        ];
        return $this->request($path, 'post', $body);
    }

    /**
     * Set the message.
     *
     * @param string $message 
     * @return \Intergo\SmsTo\SmsTo
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Set the messages.
     *
     * @param array $messages 
     * @return \Intergo\SmsTo\SmsTo
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
        return $this;
    }

    /**
     * Set Sender ID.
     *
     * @param string $senderId 
     * @return \Intergo\SmsTo\SmsTo
     */
    public function setSenderId($senderId)
    {
        $this->senderId = $senderId;
        return $this;
    }

    /**
     * Set recipients.
     *
     * @param array $recipients 
     * @return \Intergo\SmsTo\SmsTo
     */
    public function setRecipients($recipients)
    {
        $this->recipients = $recipients;
        return $this;
    }

    /**
     * Set list id.
     *
     * @param int $listId 
     * @return \Intergo\SmsTo\SmsTo
     */
    public function setListId($listId)
    {
        $this->listId = $listId;
        return $this;
    }

    /**
     * Set callback URL.
     *
     * @param string $callbackUrl 
     * @return \Intergo\SmsTo\SmsTo
     */
    public function setCallbackUrl($callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;
        return $this;
    }

    /**
     * Send Request
     *
     * @param string $path
     * @param string $method
     * @param array $data
     *
     * @return array
     */
    public function request($path, $method, $data = [])
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
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
            $response = $this->exception($e);
        } catch (RequestException $e) {
            $response = $this->exception($e);
        } catch (ClientException $e) {
            $response = $this->exception($e);
        } catch (ServerException $e) {
            $response = $this->exception($e);
        }

        return $response;
    }

    /**
     * Format exceptions message
     *
     * @param Exception $e
     *
     * @return mixed
     */
    public function exception($e)
    {
        if ($e->hasResponse()) {
            $response = $e->getResponse();
            return json_decode($response->getBody()->getContents(), true);
        }

        return false;
    }
}