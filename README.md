Documentation at https://www.contacta.mx
## Get started fast and easy!

<p align="center">
  <a href="mailto:desarrollo@contacta.mx">Issues</a> •
  <a href="https://www.contacta.mx">Need transactional SMS</a> •
  <a href="https://api.whatsapp.com/send?phone=5218141703737&text=Necesito+mas+informacion,+por+favor+me+podrian+contactar">Info, Cost and More</a> •
</p>

## Installation

```bash
> composer require contactamx/sms
```
## Getting started 

```bash
<?php

    require('vendor/autoload.php');

    use Contactamx\Sms\Bulker;
    $bulker = new Bulker();
    $bulker->setToken(YOUR_TOKEN_PROVIDED_BY_CONTACTA);
    
    // For send sms transactional
    $result = $bulker->sendMessage(YOUR_PHONE,YOUR_MESSAGE);
    if ($result->success) {
        
        echo "The id of the message is " . $result->id .PHP_EOL;
        echo "The message sent on  " . $result->added_on .PHP_EOL;
        echo "Additional data " . print_r($result->country, true) .PHP_EOL;
    
        $get_log = $bulker->check($result->id);
        print_r($get_log);
    }
    else{
        echo "Houston, we have a troubles: " .PHP_EOL. print_r($result, true) ;
    }
    
    // For send flash transactional
    $result = $bulker->sendFlash(YOUR_PHONE,YOUR_MESSAGE);
    if ($result->success) {
        
        echo "The id of the message is " . $result->id .PHP_EOL;
        echo "The message sent on  " . $result->added_on .PHP_EOL;
        echo "Additional data " . print_r($result->country, true) .PHP_EOL;
    
        $get_log = $bulker->check($result->id);
        print_r($get_log);
    }
    else{
        echo "Houston, we have a troubles: " .PHP_EOL. print_r($result, true) ;
    }

    // For send otp message
    $result = $bulker->sendOtp(YOUR_PHONE,YOUR_MESSAGE);
    if ($result->success) {
        
        echo "The id of the message is " . $result->id .PHP_EOL;
        echo "The message sent on  " . $result->added_on .PHP_EOL;
        echo "Additional data " . print_r($result->country, true) .PHP_EOL;
    
        $get_log = $bulker->check($result->id);
        print_r($get_log);
    
        $verify_otp = $bulker->verifyOtp(YOUR_PHONE,YOUR_OTP_CODE);
        print_r($result);
    }
    else{
        echo "Houston, we have a troubles: " .PHP_EOL. print_r($result, true) ;
    }
    
?>
```