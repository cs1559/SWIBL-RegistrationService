<?php
namespace swibl\services\registration;


use swibl\core\ServiceRequest;

class RegistrationServiceRequest extends ServiceRequest {
    
    var $game = null;
    
    public function setData(Registration $data)
    {
        echo get_class($data);
        $this->setData($data);
    }

    
}