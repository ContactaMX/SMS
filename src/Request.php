<?php

namespace Contactamx\Sms;

use \Contactamx\Sms\Response;
use \Contactamx\Sms\Result;
use Exception;

class Request 
{

    use \Contactamx\Sms\Validation;

    protected $response ;
    protected $error ;
    protected $headers ;

    public $body ;
    public $auth ;
    public $url;
    public $method = 'GET';

    public function __construct(array $conf = []) {

        $this->auth = $conf['auth'] ?? '';
        $this->url = $conf['url'] ?? '';

        $this->headers = [ 
            'Authorization: Basic ' . base64_encode(time() . ':' . $this->auth) ,
            'Content-Type: application/json', 
            'Accept: application/json', 
            'Connection: keep-alive', 
            'Keep-Alive: timeout=10, max=1000' ];
    }

    public function parseToValidation($res, $err)
    {
   
        $result = new OtpValidation();

        if ($err) {

            $result->error = true;
            $result->success = false;
            $result->message = json_decode($err);

            $this->response = $result;
            return $this->response;
        }
        else{
            $json = json_decode($res);

            $result->error = $json->error;
            $result->success = $json->status;
            $result->message = $json->description;

            $this->response = $result;
            return $this->response;
        }
    }

    public function parseToResult($res, $err)
    {
   
        $result = new Result();

        if ($err) {

            $result->error = true;
            $result->success = false;
            $result->description = json_decode($err);

            $this->response = $result;
            return $this->response;
        }
        else{
            $json = json_decode($res);

            $result->error = false;
            $result->success = $json->status;
            // $response->data = $json->description;

            if ($json->status) {
                $object =  $json->description->message;

                $result->id = $object->control;
                $result->message = $object->msg;
                $result->sent_at = $object->send_date;
                $result->added_at = $object->received_date;
                $result->tracer = $object->campo_a;
                $result->status = $object->status;
                $result->disposition = $object->disposicion;
                $result->carrier = $object->carrier;
                $result->answers = $json->description->answers;
            }

            $this->response = $result;
            return $this->response;
        }
    }

    public function get()
    {
        $this->verify_url($this->url);
        
        $curl = curl_init();
        $options = [
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 0,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => $this->headers,
        ];

        curl_setopt_array($curl, $options);

        $res = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if (strpos($this->url, 'otp/verify')) {
            return $this->parseToValidation($res, $err);
        } else {
            return $this->parseToResult($res, $err);
        }
    }

    public function post()
    {
        $this->verify_auth($this->auth);
        $this->verify_url($this->url);
        $this->verify_request($this->body);
        
        $curl = curl_init();
        $options = [
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 0,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $this->method,
            CURLOPT_POSTFIELDS => json_encode($this->body),
            CURLOPT_HTTPHEADER => $this->headers,
        ];

        curl_setopt_array($curl, $options);

        $res = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        
        $response = new Response();

        if ($err) {

            $response->error = true;
            $response->success = false;
            $response->message = json_decode($err);

            $this->response = $response;
            return $this->response;
        }
        else{
            $json = json_decode($res);

            $response->error = $json->error;
            $response->success = $json->status;
            $response->message = $json->description;

            if ($json->status) {
                $response->message = "message sent successfully";
                $response->id = $json->description->id;
                $response->country = $json->description->country;
                $response->charge = $json->description->charge;
                $response->added_on = $json->description->added_on;
            }

            $this->response = $response;
            return $this->response;
        }
    }

    public function getResponse()
    {
        return $this->response;
    }
}
