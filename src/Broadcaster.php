<?php

namespace Skvrd\LaravelBroadcaster;

class Broadcaster
{
    private $api_token;
    private $host;
    private $debug = false;

    public function __construct($api_token, $host)
    {
        $this->api_token   = $api_token;
        $this->host         = $host;
    }
    
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }
    
    public function broadcast($channel, $event, $data)
    {
        //We need to do some validation here by the end of the day
        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->post($this->host."/api/events", [
                'headers'        => ['Accept' => 'aplication/json'],
                'debug'          => $this->debug,
                'body' => [
                    'api_token'     => $this->api_token,
                    'channel'       => $channel,
                    'event'         => $event,
                    'data'          => json_encode($data)
                ]
            ]);
            if ($this->debug) {
                var_dump($res->getBody()->getContents());
            }
            return true;           
        } 
        catch (GuzzleHttp\Exception\ClientException $e)
        {
            return false;
            //do nothing
        } 
        catch (GuzzleHttp\Exception\ServerException $e)
        {
            return false;
            //probably need to notify :)
        }

        
    }
}