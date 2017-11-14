<?php 
namespace swibl\services\registration\actions;

use Slim\Container;
use Exception;
use swibl\core\Error;
use swibl\core\exception\RecordNotFoundException;
use swibl\services\registration\RegistrationService;
use swibl\services\registration\RegistrationDAO;
use swibl\services\registration\RegistrationServiceResponse;

class GetRegistrationAction
{
   protected $container;
   
   public function __construct(Container $container) {
       $this->container = $container;
   }
   
   public function __invoke($request, $response, $args) {
   
        $service = RegistrationService::getInstance();
        $logger = $service->getLogger();
        $logger->info("GET /" . $request->getAttribute('id') );
        $dao = RegistrationDAO::getInstance($service->getDatabase());
       
        try {
            $object = $dao->get($request->getAttribute('id'));
//             $logger->debug("Building Registration " . $request->getAttribute('id') . " Object");
//             $builder = new RegistrationBuilder();
//             $object = $builder->build($result);
//             $logger->debug("Registration object " . $request->getAttribute('id') . " built");
            
            $svcresponse = RegistrationServiceResponse::getInstance(200,"Record retrieved");
            $svcresponse->setData($object);
            $response->write(json_encode($svcresponse));
        }
        catch (RecordNotFoundException $e) {
            $svcresponse = RegistrationServiceResponse::getInstance(400, $e->getMessage());
            $response->write(json_encode($svcresponse));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
        catch (Exception $e) {
            $error = new Error();
            $error->setSourcefile("file: " . $e->getFile() . " Line#: " . $e->getLine());
            $error->setMethod("GET /{id}");
            $error->setInternalMessage($e->getMessage());
            $svcresponse = RegistrationServiceResponse::getInstance(400, $e->getMessage());
            $svcresponse->addError($error);
            $response->write(json_encode($svcresponse));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
        
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
   }
}
?>