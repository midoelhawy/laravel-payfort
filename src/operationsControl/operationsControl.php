<?php
namespace LaravelPayfort\operationsControl;

class operationsControl {
    protected $response;
    protected $successCodes = [];

    function __construct($response) {
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function isSuccess()
    {
        $status = isset($this->response->status) ? intval($this->response->status):null;
        //return [$status,$this->successCodes];
        if (in_array($status,$this->successCodes)) {
            return true;
        }
        return false;
    }


    public function responseMsg()
    {
        if (isset($this->response->response_message)) {
            return $this->response->response_message;
        }

        return "";
    }

}

?>
