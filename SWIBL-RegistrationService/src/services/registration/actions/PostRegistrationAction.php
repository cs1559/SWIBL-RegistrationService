<?php
namespace swibl\services\registration\actions;


use Slim\Container;
use Exception;
use swibl\services\registration\RegistrationService;
use swibl\services\registration\RegistrationDAO;
use swibl\services\registration\RegistrationBuilder;
use swibl\services\registration\RegistrationServiceResponse;

class PostRegistrationAction
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
        $logger->info("POST /" . $content );
        
        //         $content2 = $request->getParsedBody();
        
        $dao = RegistrationDAO::getInstance($service->getDatabase());
        try {
            $builder = new RegistrationBuilder();
            $logger->debug("REQUEST CONTENT: " . $content);
            $registration = $builder->build(json_decode($content));
            
            $registration->setConfirmationNumber(uniqid());
            
            $newid = $dao->insert($registration);
            $registration->setId($newid);

            // SEND EMAIL CONFIRMATION
            if ($service->isMailEnabled()) {                          
                if ($service->isDebugEnabled()) {
                    $config = $service->getConfig();
                    $fromEmail= $config->getPropertyValue("debug.email");
                } else {
                    $fromEmail = $registration->getEmail();
                }
                $logger->info("Sending registration confirmation to " . $fromEmail);
                $mailer = $service->getMailer();
                $mailer->send($fromEmail, "SWIBL - Registration Confimration", "test email message");
            }
            
            $svcresponse = new RegistrationServiceResponse(200, "Record " . $newid . " has been created",$registration);
            $response->write(json_encode($svcresponse));
        }
        catch (Exception $e) {
            $logger->error("POST /" . $e->getTraceAsString() );
            $svcresponse = new RegistrationServiceResponse(400, $e->getMessage());
            $response->write(json_encode($svcresponse));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
        
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        
        
    }
}
?>