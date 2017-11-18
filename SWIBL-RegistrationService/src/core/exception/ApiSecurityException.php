<?php
namespace swibl\core\exception;

class ApiSecurityException extends \Exception {
    
    function __construct() {
        parent::__construct("Not Authorized", "401");
    }
    
    function __contruct($message) {
        parent::__construct($message, "401");
    }
}