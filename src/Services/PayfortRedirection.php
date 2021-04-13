<?php

namespace LaravelPayfort\Services;

use LaravelPayfort\Traits\PayfortRedirectRequest;


class PayfortRedirection extends Payfort
{

    use PayfortRedirectRequest;

    /**
     * Payfort API Processor Constructor.
     *
     * @param $config
     */
    public function __construct($config)
    {
        # Call parent constructor to initialize common settings
        parent::__construct($config);

        $isTest = env("PAYFORT_USE_TEST_ENV", false);
        if($isTest){
            $this->payfortEndpoint = 'https://sbcheckout.payfort.com/FortAPI/paymentPage';
        }else{
            $this->payfortEndpoint = 'https://checkout.payfort.com/FortAPI/paymentPage';
        }



        $this->config['return_url'] = url($this->config['return_url']);
    }
}
