<?php
namespace swibl\services\registration;

use Psr\Http\Message\ServerRequestInterface;
use swibl\core\RequestAuthorizer;

class RegistrationRequestAuthorizer extends RequestAuthorizer {
 
    function __construct() {
        self::setService(RegistrationService::getInstance());
    }
 
    function checkServiceAuthorizations(ServerRequestInterface $request) {
        return true;
    }
    
}