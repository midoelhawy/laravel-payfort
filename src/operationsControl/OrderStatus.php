<?php
namespace LaravelPayfort\operationsControl;

class OrderStatus extends operationsControl{
    protected $successCodes = [12];

    public function isSuccess()
    {
        $status = isset($this->response->status) ? intval($this->response->status):null;
        if (in_array($status,$this->successCodes) && $this->response->transaction_status != "06") {
            return true;
        }
        return false;
    }


    public function isRefunded()
    {
        return $this->response->transaction_status == "06";
    }


}

?>
