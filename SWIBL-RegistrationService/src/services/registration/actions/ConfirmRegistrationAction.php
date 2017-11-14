<?php
namespace swibl\services\registration\actions;

use Slim\Container;
use Exception;
use swibl\services\registration\RegistrationService;
use swibl\services\registration\RegistrationDAO;
use swibl\services\registration\RegistrationServiceResponse;


class ConfirmRegistrationAction
{
    protected $container;
    
    public function __construct(Container $container) {
        $this->container = $container;
    }
    
    public function __invoke($request, $response, $args) {
        
        $service = RegistrationService::getInstance();
        
        $id = $request->getAttribute("id");
        $confnum = $request->getAttribute("confnum");
        
        $body = $request->getBody();
        $content = $body->getContents();
        
        $logger = $service->getLogger();
        $logger->info("CONFIRM /" . $request->getAttribute('id') . " CONF NUM=" . $confnum);
        
        $dao = RegistrationDAO::getInstance($service->getDatabase());
        try {
            $reg = $dao->get($id);
            
            if ($confnum != $reg->getConfirmationNumber()) {
                $logger->error("INVALID CONFIRMATION NUMBRER");
                $svcresponse = RegistrationServiceResponse::getInstance(400, "INVALID CONFIRMATION NUMBRER");
                $response->write(json_encode($svcresponse));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }

            $dao->confirm($id);
            $svcresponse = RegistrationServiceResponse::getInstance(200, "Record " . $id . " has been successfully confirmed by user");
            $response->write(json_encode($svcresponse));
        }
        catch (Exception $e) {
            $logger->info("CONFIRM /" . $e->getTraceAsString() );
            $svcresponse = RegistrationServiceResponse::getInstance(400, $e->getMessage());
            $response->write(json_encode($svcresponse));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');

        }
        
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');

    }
}
?>