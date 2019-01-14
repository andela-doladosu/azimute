<?php

namespace XmlTime;

class XmlTime
{
    private $accesskey;
    private $secretkey;

    private $entrypoint = 'http://api.xmltime.com/';
    private $apiFormat = 'xml';
    private $apiVersion = 1;

    private $signature;

    public function __construct($accesskey, $secretkey, XmlTimeParser $xmlTimeParser)
    {
        $this->accesskey = $accesskey;
        $this->secretkey = $secretkey;
        $this->parser = $xmlTimeParser;
    }

    public static function create($accesskey, $secretkey, $xmlTimeParser)
    {
        return new self($accesskey, $secretkey, $xmlTimeParser);
    }

    public function servicecall($service, $args)
    {
        $timestamp = gmdate('c');

        $args['signature'] = $this->createSignature($service, $timestamp);
        $args['accesskey'] = $this->accesskey;
        $args['timestamp'] = $timestamp;
        $args['out'] = $this->apiFormat;
        $args['version'] = $this->apiVersion;


        $url = "$this->entrypoint/$service?" . http_build_query($args);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);


        curl_close($ch);

        return $result;
    }

    private function createSignature($service, $timestamp)
    {
        $message = $this->accesskey.$service.$timestamp;

        return base64_encode(hash_hmac('sha1', $message, $this->secretkey, true));
    }

    public function getLocationData($response)
    {
        return $this->parser->getLocationDataFromResponse($response);
    }
}
