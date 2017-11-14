<?php
namespace swibl\services\registration\actions;

use Slim\Container;
use Exception;
use swibl\services\registration\RegistrationService;
use swibl\services\registration\RegistrationDAO;
use swibl\services\registration\RegistrationServiceResponse;


class DeleteRegistrationAction
{
    protected $container;
    
    public function __construct(Container $container) {
        $this->container = $container;
    }
    
    public function __invoke($request, $response, $args) {
        
        $service = RegistrationService::getInstance();
        
        $id = $request->getAttribute("id");
        $body = $request->getBody();
        $content = $body->getContents();
        
        $content2 = $request->getParsedBody();
        
        $logger = $service->getLogger();
        $logger->info("DELETE /" . $request->getAttribute('id') );
        $dao = RegistrationDAO::getInstance($service->getDatabase());
        try {
            $dao->delete($id);
            $svcresponse = RegistrationServiceResponse::getInstance(200, "Record " . $id . " has been successfully deleted");
            $response->write(json_encode($svcresponse));
        }
        catch (Exception $e) {
            $logger->info("DELETE /" . $e->getTraceAsString() );
            $svcresponse = RegistrationServiceResponse::getInstance(400, $e->getMessage());
            $response->write(json_encode($svcresponse));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');

        }
        
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');

    }
}
?>