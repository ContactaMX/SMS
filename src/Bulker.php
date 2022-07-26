<?php

    namespace Contactamx\Sms;

    use Exception;
    use \Contactamx\Sms\Request;

    class Bulker 
    {
        use \Contactamx\Sms\Validation;

        const ENDPOINT = "https://sms.contacta.mx/api/";
        const VERSION = 2;

        private $token;
        private $endpoint;
        
        public function __construct()
        {
            $this->endpoint = Bulker::ENDPOINT . 'v' . Bulker::VERSION . '/';
        }

        public function setToken($token)
        {            
            $this->token = $token;
        }

        public function sendMessage(string $destination = '', string $message = '', array $options = [])
        {

            $this->verify_auth($this->token);
            $this->verify_destination($destination);
            $this->verify_content($message);

            $this->local = $options['local'] ?? true;
            $this->tracer = $options['tracer'] ?? '';

            $this->route = $this->endpoint . (($this->local) ? 'sms' : 'sms-global') . '/send';
            $this->destination = ($this->local) ? substr($destination,2,10)  : $destination;

            $this->package = [ "phone" => $destination, "msg" => $message, "id" => $this->tracer];

            $request = new Request([ "auth" => $this->token, "url" => $this->route ]);
            $request->method = "POST";
            $request->body = $this->package;
            $request->post();

            return $request->getResponse();
        }

        public function sendFlash(string $destination = '', string $message = '', array $options = [])
        {

            $this->verify_auth($this->token);
            $this->verify_destination($destination);
            $this->verify_content($message);

            $this->local = $options['local'] ?? true;
            $this->tracer = $options['tracer'] ?? '';

            $this->route = $this->endpoint . (($this->local) ? 'flash' : 'flash-global') . '/send';
            $this->destination = ($this->local) ? substr($destination,2,10)  : $destination;

            $this->package = [ "phone" => $destination, "msg" => $message, "id" => $this->tracer];

            $request = new Request([ "auth" => $this->token, "url" => $this->route ]);
            $request->method = "POST";
            $request->body = $this->package;
            $request->post();

            return $request->getResponse();
        }
        
        public function sendOtp(string $destination = '', string $message = '', array $options = [])
        {

            $this->verify_auth($this->token);
            $this->verify_destination($destination);
            // $this->verify_content($message);

            $this->local = $options['local'] ?? true;
            $this->tracer = $options['tracer'] ?? '';

            $this->route = $this->endpoint . (($this->local) ? 'otp' : 'otp-global') . '/send';
            $this->destination = ($this->local) ? substr($destination,2,10)  : $destination;

            $this->package = [ "phone" => $destination, "id" => $this->tracer];

            if (strlen($message) > 0 ) {
                $this->package['msg'] = $message;
            }

            $request = new Request([ "auth" => $this->token, "url" => $this->route ]);
            $request->method = "POST";
            $request->body = $this->package;
            $request->post();

            return $request->getResponse();
        }

        
        public function check(string $search_id = '')
        {

            $this->verify_auth($this->token);
            $this->verify_content($search_id);

            $this->route = $this->endpoint . 'sms/check';
            $this->package = [ "auth" => $this->token, "token" => $search_id];

            $request = new Request([ "auth" => $this->token, "url" => $this->route . '?' . http_build_query($this->package) ]);
            $request->method = "GET";
            $request->get();

            return $request->getResponse();
        }

        public function verifyOtp(string $destination = '', string $otp_code)
        {

            $this->verify_auth($this->token);
            $this->verify_destination($destination);
            $this->verify_content($otp_code);

            $this->route = $this->endpoint . 'otp/verify';
            $this->package = [ "auth" => $this->token, "phone" => $destination, "otp" => $otp_code];

            $request = new Request([ "auth" => $this->token, "url" => $this->route . '?' . http_build_query($this->package) ]);
            $request->method = "GET";
            $request->get();

            return $request->getResponse();
        }
    }
    