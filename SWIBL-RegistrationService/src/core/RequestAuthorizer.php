<?php
namespace swibl\core;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use swibl\core\authentication\AuthDAO;
use swibl\core\exception\ApiSecurityException;

/**
 * This class is a SLIM middleware component for the service to authenticate the incoming request.  If the request cannot be
 * authenticated, then return a 401 message.
 * @author Admin
 *
 */
abstract class RequestAuthorizer {
    
    var $service = null;
    
    /**
     * @return the $service
     */
    public function getService()
    {
        return $this->service;
    }
    
    /**
     * @param field_type $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }
    
    public function __invoke(\Psr\Http\Message\ServerRequestInterface $request,
        \Psr\Http\Message\ResponseInterface $response,
        $next)
    {
        
        $service = $this->getService();
        $logger = $service->getLogger();
        $logger->info("authentiating " . $_SERVER['REQUEST_METHOD'] . " request");
        $logger->info("Current request URI: " . $_SERVER['REQUEST_URI']);
        
        // Support a global permission for all GET requests.
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            return $next($request, $response);
        }
        
        if ($service->isAuthenticationEnabled()) {
            // Authenticate the request.
            try {
                
                $authenticated = $this->authenticateRequest($request);
                if (!$authenticated) {
                    return $response->withStatus(401,"Unauthorized request");
                }
            } catch (ApiSecurityException $e) {
                $logger->info("AUTENTCATION EXCEPTION CAUGHT " . $e->getMessage());
                return $response->withStatus(401,$e->getMessage());
            }
        }
        return $next($request, $response);
    }
    
    /**
     * This function will execute application specific logic to authenticate the incoming request to the service.
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return boolean
     */
    function authenticateRequest(\Psr\Http\Message\ServerRequestInterface $request) {
        $service = $this->getService();
        $logger = $service->getLogger();
        $logger->debug("Inside authenticateRequest method");
        
        $db = $service->getDatabase();
        $clientid = $request->getHeaderLine("PHP_AUTH_USER");
        
        $dao = AuthDAO::getInstance($db);
        
        $logger->info("Authentication API call for " . $clientid);
        
        try {
            $token = $dao->getAUthToken($clientid);
            $logger->info("KEY  " . $token->getConsumerKey());
            $secret = $token->getConsumersecret();
        } catch (\Exception $e) {
            $logger->info("Client " . $clientid . " did not have an authtoken granted");
            $secret="";
            throw new ApiSecurityException("API SECURITY ERROR: NO AUTHTOKEN FOUND");
        }
               
        $signature = $request->getHeaderLine("HTTP_SIGNATURE");
        $nonce = $request->getHeaderLine("HTTP_NONCE");
        $key = $request->getHeaderLine("PHP_AUTH_PW");
        
        $calculated_signature = base64_encode(hash_hmac("sha256", $key . ":" . $nonce, $secret, True));
        
        if ($logger->getLevel() > 2) {
            $headers = $request->getHeaders();
            foreach ($headers as $name => $values) {
                $logger->debug($name . ": " . implode(", ", $values));
            }
            $logger->debug("REQUEST SIGNATURE /" . $request->getHeaderLine("HTTP_SIGNATURE"));
            $logger->debug("CALCULATED SIGNATURE = " . $calculated_signature);
        }
        
        if ($signature != $calculated_signature) {
            $logger->error("[Client " . $clientid . "] INVALID SIGNATURE ".$_SERVER['REQUEST_METHOD'] );
            return false;
        } else {
            $logger->debug("REQUEST SIGNATURE MATCHED");
        }
        
        switch ($_SERVER['REQUEST_METHOD']) {
            case "POST":
                if ($token->canPost()) return true;
                $logger->error("[Client " . $clientid . "] NOT AUTHORIZED FOR ".$_SERVER['REQUEST_METHOD'] . " OPERATION" );
                return false;
                break;
            case "PUT":
                if ($token->canPut()) return true;
                $logger->error("[Client " . $clientid . "] NOT AUTHORIZED FOR ".$_SERVER['REQUEST_METHOD'] . " OPERATION" );
                return false;
                break;
            case "DELETE":
                if ($token->canDelete()) return true;
                $logger->error("[Client " . $clientid . "] NOT AUTHORIZED FOR ".$_SERVER['REQUEST_METHOD'] . " OPERATION" );
                return false;
                break;
        }
        
        // Check SERVICE SPECIFIC AUTHORIZATION RULES
        if (!self::checkServiceAuthorizations($request)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * This method is used to perform any specific service authenticaiton steps as necessary.
     * 
     * @param ServerRequestInterface $request
     */
    abstract function checkServiceAuthorizations(ServerRequestInterface $request);
    
}