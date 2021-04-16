Laravel Payfort Package
=======================
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md) [![laravel version](https://img.shields.io/static/v1?label=laravel&message=7.0&color=red&style=flat-square)](https://laravel.com/docs/7.x/releases)  [![lib version](https://img.shields.io/static/v1?label=payfort&message=2.0.0&color=orange&style=flat-square)](https://laravel.com/docs/7.x/releases)


`Laravel Payfort` provides a simple and rich way to perform and handle operations for 
`Payfort` (MEA based online payment gateway) check here to read more [Payfort](http://www.payfort.com/).  
This package supports a set of `Payfort` operations as listed below, other operations are open for future work and 
contribution. 
* AUTHORIZATION/PURCHASE
* CAPTURE
* TOKENIZATION
* SDK_TOKEN
* CHECK_STATUS
* REFUND


You have to read the `Payfort` documentation very well before proceeding in using any package, the package author 
will not write about `Payfort` operations, what and how to use.
 
## Install

You can install `Laravel Payfort` package to your laravel project via composer command:
```
$ composer require midoelhawy/laravel-payfort
```

## Configuration

#####  For Laravel > 7.0 

After installing the `Laravel Payfort` library, register the `LaravelPayfort\Providers\PayfortServiceProvider` 
in your `config/app.php` configuration file:

```php
'providers' => [
    // Other service providers...
    LaravelPayfort\Providers\PayfortServiceProvider::class,
],
```

Also, add the `Payfort` facade to the `aliases` array in your `app` configuration file:
```php
'Payfort' => LaravelPayfort\Facades\Payfort::class
```

After that, run the following command to publish the configurations file:
```
$ php artisan vendor:publish --provider "LaravelPayfort\Providers\PayfortServiceProvider"
```
 This will create a new config file named `payfort.php` in `config` folder. Then you have to add the following 
 constants in the `.env` file, you can find most of these values in your `Payfort` account. 
 ```
PAYFORT_USE_SANDBOX=true                    #Defines wether to activate the payfort sandbox env or not.
PAYFORT_USE_TEST_ENV=true                   #Define Test env mode for development
PAYFORT_MERCHANT_IDENTIFIER=51e316554       # The payfort merchant account identifier
PAYFORT_ACCESS_CODE=XwMv28sHSAkSaB71uGON    # The payfort account access code
PAYFORT_SHA_TYPE=sha256                     # The payfort account sha type. sha256/sha512
PAYFORT_SHA_REQUEST_PHRASE=58obfoddsfg..    # The payfort account sha request phrase
PAYFORT_SHA_RESPONSE_PHRASE=86md5f56s..     # The payfort account sha response phrase
PAYFORT_CURRENCY=SAR                        # The default currency for you app. Currency ISO code 3.
PAYFORT_LANGUAGE=EN                         # The system default langauge for response messages
PAYFORT_RETURN_URL=/payfort/response        # The url to return after submitting payfort forms.

 ```
 
## Basic Usage

Once all configuration steps are done, you are ready to use payfort operations in your app. Here is some examples on 
how to use this package:
 
 
### Authorization/Purchase request (Redirection)

To display payfort authorization or purchase page, in your controller's method add the following code snippet:
```php
return Payfort::redirection()->displayRedirectionPage([
    'command' => 'AUTHORIZATION',              # AUTHORIZATION/PURCHASE according to your operation.
    'merchant_reference' => 'ORDR.'.rand(10000,100000),   
    'amount' => 3501.35,                           
    'currency' => 'SAR',                      
    'customer_email' => 'example@example.com',  
    'payment_option' => 'VISA', //Mada and others types 
    "language"=>'ar',
    'return_url'=>"https://expm.com/response"
]);
```

> **⚠ Note**  
> Mada payment method works only as PURCHASE (not as AUTHORIZATION).

Other optional parameters that can be passed to `displayRedirectionPage` method as follows:
* token_name
* sadad_olp
* eci
* order_description
* customer_ip
* customer_name
* merchant_extra
* merchant_extra1
* merchant_extra2
* merchant_extra3

`Payfort` page will be displayed and once user submits the payment form, the return url defined in the environment 
configurations will be called.

![GitHub Logo](https://i.stack.imgur.com/S8NZW.png)





See [`Payfort` documentation](https://docs.payfort.com/docs/redirection/build/index.html#authorization-purchase-request) for more info.

### Tokenization request

To display payfort tokenization page, in your controller's method add the following code snippet:
```php
return Payfort::redirection()->displayTokenizationPage([
    'merchant_reference' => 'ORDR.34562134',   # You reference id for this operation (Order id for example).
]); 
```

`Payfort` page will be displayed and once user submits the payment form, the return url defined in the config file 
will be called.


### Capture Payment

To Capture after callback AUTHORIZATION, in your controller's method add the following code snippet:
```php
$checkpayfort->captureOperationByFortId(
                [
                    "fort_id"=>$payfort_return["fort_id"],
                    "merchant_reference"=>$payfort_return["merchant_reference"],
                    "amount"=>3501.35,//your amount to capture (max : the authraized amount)
                    "currency"=>"SAR",
                ]
);
```
See [`Payfort` documentation](https://docs.payfort.com/docs/other-payfort-services/build/index.html#fort-tokenization-service) for more info.

### Handling Payfort Authorization/Purchase response

#### Handling callback (return)

In your handling controller that handle the return url, you can simply use the `PayfortResponse` trait as follows:
```
use LaravelPayfort\Traits\PayfortResponse as PayfortResponse;

class PayfortOrdersController extends Controller{
    use PayfortResponse;
    
    public function processReturn(Request $request){
        $payfort_return = $this->handlePayfortCallback($request);
        $checkpayfort =new PayfortAPI(config('payfort'));
        $checkStatus = $checkpayfort->checkOrderStatusByFortId($payfort_return["fort_id"]);
        if($checkStatus->isSuccess()){
            return $checkStatus->getResponse();//return payfort json array 
        }
        
        if($payfort_return["command"] == "AUTHORIZATION"){
            $captureAuthorizaPymnt = $checkpayfort->captureOperationByFortId(
                [
                    "fort_id"=>$payfort_return["fort_id"],
                    "merchant_reference"=>$payfort_return["merchant_reference"],
                    "amount"=>3501.35,//your amount to capture (max : the authraized amount)
                    "currency"=>"SAR",
                ]
            );
            
            if($captureAuthorizaPymnt->isSuccess()){
                //success payment
            }
            //....
            
        }
        
    }
}
```

See [`Payfort` documentation](https://docs.payfort.com/docs/redirection/build/index.html#authorization-purchase-response) for more info.


#### Handling Direct Transaction Feedback

Same as handling payfort response except that you have to call `handlePayfortFeedback` instead of `handlePayfortCallback` 
 
## Contribution
 Want to improve this package or found a bug ?. Open an issue or do this contribution by yourself and get this honor.

Simply, fork => do you work => make pull request.

Write clear comments and description ;-).


## License
 
`Laravel Payfort` is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
# laravel-payfort

This Library implemented on :
- [wshurafa](https://github.com/wshurafa/laravel-payfort)
- [roaatech](https://github.com/roaatech/payfort-php)

