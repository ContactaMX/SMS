<?php

    namespace Contactamx\Sms;
    use Exception;

    trait Validation
    {
        public function verify_destination($destination)
        {
            if ($destination == '0000000000' || strlen($destination) < 10 ) {
                throw new Exception("Auth is invalid or missing, please fill auth in setToken method", 1);
            };
        }

        public function verify_content(string $body)
        {
            if( strlen($body) <= 3 ) {
                throw new Exception("Auth is invalid or missing, please fill auth in setToken method", 1);
            };
        }

        public function verify_auth(string $auth)
        {
            if (( strlen($auth) <= 10 ) || $auth == null){
                throw new Exception("Auth is invalid or missing, please fill auth in setToken method", 1);
            };
        }

        public function verify_method(string $method)
        {
            if(!in_array( strtoupper($method), ["GET", "POST", "PUT", "DELETE"])){
                throw new Exception("Auth is invalid or missing, please fill auth in setToken method", 1);
            };    
        }

        public function verify_request(array $body)
        {
            if(!array_key_exists("phone", $body) || !array_key_exists("msg", $body)){
                throw new Exception("The body package is empty or missing phone / msg key", 1);
            };
        }

        public function verify_url(string $url)
        {
            $parse = parse_url($url);
            $result = dns_get_record($parse['host']);

            if (count($result)==0) {
                throw new Exception("Invalid endpoint url", 1);
            }
        }
    }
    