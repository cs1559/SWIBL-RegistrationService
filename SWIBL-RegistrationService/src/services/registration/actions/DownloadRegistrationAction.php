<?php 
namespace swibl\services\registration\actions;

use Slim\Container;
use Exception;
use swibl\core\Error;
use swibl\core\exception\RecordNotFoundException;
use swibl\services\games\GameHelper;
use swibl\services\games\GameService;
use swibl\services\games\GameServiceResponse;
use swibl\services\games\GamesDAO;
use swibl\services\registration\RegistrationHelper;
use swibl\services\registration\RegistrationService;
use swibl\services\registration\RegistrationDAO;


/**
 * This class creates a downloable schedule.
 * @author Admin
 *
 */
class DownloadRegistrationAction
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
       $logger->info("/Registration/download " . $request->getUri() );
       
       $season = $request->getAttribute("seasonid");
             
       $dao = RegistrationDAO::getInstance($service->getDatabase());
       try {
           $results = $dao->getRegistrations($season);

           $registrations = RegistrationHelper::bindArray($results);
           
         
           // Header = game#, game date, game_time, hometeam
           $headerRow = "id, Age Group, Team, Coach, Address, City, State, Email, Phone, Cell Phone, Requested Division, Tournament, All-Star, Conf Number";
           $response->write($headerRow . "\r\n");
           
           // Build formatted rows.
           foreach ($registrations as $reg) {
               if ($reg->isPlayingInTournament()) {
                   $tval = "Yes";
               } else {
                   $tval = "No";
               }
               if ($reg->isPlayingInAllStarEvent()) {
                   $aval = "Yes";
               } else {
                   $aval = "No";
               }
               $tmp = array(
                   $reg->getId(),
                   $reg->getAgeGroup(),
                   $reg->getTeamName(),
                   $reg->getName(),
                   str_replace(","," ",$reg->getAddress()),
                   str_replace(","," ",$reg->getCity()),
                   $reg->getState(),
                   $reg->getEmail(),
                   $reg->getPhone(),
                   $reg->getCellPhone(),
                   $reg->getDivisionClass(),
                   $tval,
                   $aval,
                   $reg->getConfirmationNumber()   
               );
               $regcsv = implode(",", $tmp);
               $response->write($regcsv . "\r\n");
           }
           return $response->withHeader('Content-Type','text/csv')->withHeader('Content-Disposition', 'attachment; filename=registrations.csv');
       }
       catch (RecordNotFoundException $e) {
           $logger->info("TEAM SCHEDULE NOT FOUND - " . $request->getUri());
           $svcresponse = new GameServiceResponse(400, $e->getMessage());
           $response->write(json_encode($svcresponse));
           return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
       }
       catch (Exception $e) {
           $error = new Error();
           $error->setSourcefile("file: " . $e->getFile() . " Line#: " . $e->getLine());
           $error->setMethod("GET /{id}");
           $error->setInternalMessage($e->getMessage());
           $svcresponse = new GameServiceResponse(400, $e->getMessage());
           $svcresponse->addError($error);
           $response->write(json_encode($svcresponse));
           return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
       }
       
       return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
       
  
   }
}
?>