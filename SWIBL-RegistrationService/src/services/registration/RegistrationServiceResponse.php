<?php
namespace swibl\services\registration;

/**
 *
 */
 
class RegistrationServiceResponse extends \swibl\core\ServiceResponse {
    
     /**
     * The constructor will build a Service Response object accepting a Game object as an argument.  This will
     * ensure that the "data" within the repsonse message will always bean instance of a Game.
     * 
     * @param Registration $content
     */
    public function __construct($code, $message, Registration $content = null)
    {
        $this->setCode($code);
        $this->setMessage($message);
        $this->data = $content;
    }
    
    public static function getInstance($code, $message) {
        return new RegistrationServiceResponse($code, $message);
    }

    public function getData()
    {
        return $this->data;
    }
    public function setData($data)
    {
        $this->data = $data;
    }
    
}