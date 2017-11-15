<?php
namespace swibl\services\registration\actions;


use Slim\Container;
use Exception;
use swibl\services\registration\RegistrationService;
use swibl\services\registration\RegistrationDAO;
use swibl\services\registration\RegistrationBuilder;
use swibl\services\registration\RegistrationServiceResponse;


class PutRegistrationAction
{
    protected $container;
    
    public function __construct(Container $container) {
        $this->container = $container;
    }
    
    public function __invoke($request, $response, $args) {
        $service = RegistrationService::getInstance();
        $body = $request->getBody();
        $content = $body->getContents();
        
        $logger = $service->getLogger();
        $logger->info("PUT /" . $content );
        
   
        $id = $request->getAttribute("id");
        
        $dao = RegistrationDAO::getInstance($service->getDatabase());
        try {
            $builder = new RegistrationBuilder();
            $logger->debug( $content);
            $reg = $builder->build(json_decode($content));
            $dao->update($reg);
            $svcresponse = new RegistrationServiceResponse(200, "Record " . $reg->getId() . " has been updated",$reg);
            $response->write(json_encode($svcresponse));
        }
        catch (Exception $e) {
            $logger->error("PUT /" . $e->getTraceAsString() );
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
        
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');

    }
}
?>