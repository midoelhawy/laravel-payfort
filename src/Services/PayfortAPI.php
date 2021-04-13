<?php

namespace LaravelPayfort\Services;

use GuzzleHttp\Client;
use LaravelPayfort\Traits\PayfortAPIRequest;


class PayfortAPI extends Payfort
{

    use PayfortAPIRequest;

    /**
     * The HTTP Client instance.
     *
     * @var \GuzzleHttp\Client
     */
    private $httpClient;

    /**
     * Payfort API Processor Constructor.
     *
     * @param $config
     */
    public function __construct($config)
    {
        # Call parent constructor to initialize common settings
        parent::__construct($config);

        $this->payfortEndpoint = 'https://paymentservices.payfort.com/FortAPI/paymentApi';



        $isTest = env("PAYFORT_USE_TEST_ENV", false);
        if($isTest){
            $this->payfortEndpoint = 'https://sbpaymentservices.payfort.com/FortAPI/paymentApi';
        }
        # Setting Http Client
        $this->httpClient = new Client([
            'curl' => [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CONNECTTIMEOUT => false
            ],
            'headers' => [
                'Accept' => 'application/json'
            ],
        ]);
    }

}
