<?php 
namespace swibl\services\registration\actions;

use Slim\Container;
use Exception;
use swibl\core\Error;
use swibl\core\exception\RecordNotFoundException;
use swibl\services\registration\RegistrationHelper;
use swibl\services\registration\RegistrationService;
use swibl\services\registration\RegistrationDAO;
use swibl\services\registration\RegistrationServiceResponse;


/**
 * This class creates a downloable schedule.
 * @author Admin
 *
 */
class GetAllRegistrationAction
{
   protected $container;
   
   public function __construct(Container $container) {
       $this->container = $container;
   }
   
   public function __invoke($request,  $response, $args) {
  
       $service = RegistrationService::getInstance();
       $body = $request->getBody();
       $content = $body->getContents();
       
       $logger = $service->getLogger();
       $logger->info("GET ALL REGISTRATIONS " . $request->getUri() );
       
       $season = $request->getAttribute("seasonid");
             
       $dao = RegistrationDAO::getInstance($service->getDatabase());
       try {
            $registrations = $dao->getRegistrations($season);
            $returnArray = array();
            
            foreach ($registrations as $reg) {
                $returnArray[] = json_encode($reg);
            }
            
            print_r($returnArray);
            exit;

           return $response->withHeader('Content-Type','text/csv')->withHeader('Content-Disposition', 'attachment; filename=registrations.csv');
       }
       catch (RecordNotFoundException $e) {
           $logger->info("TEAM SCHEDULE NOT FOUND - " . $request->getUri());
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