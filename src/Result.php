<?php

namespace Contactamx\Sms;

class Result {

    public $error = false;
    public $success = true;
    // public $data = [];

    public $id = null;
    public $phone = null;
    public $message = null;
    public $sent_at = null;
    public $added_at = null;
    public $tracer = null;
    public $status = null;
    public $disposition = null;
    public $carrier = null;
    public $answers = [];

}