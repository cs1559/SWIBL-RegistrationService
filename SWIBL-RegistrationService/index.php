<?php

/**
 * This is the main contorller for the GameService.
 */



use swibl\services\registration\actions\DownloadRegistrationAction;
use swibl\services\registration\actions\GetRegistrationAction;
use swibl\services\registration\RegistrationRequestAuthorizer;

require 'vendor/autoload.php';

// Bootstrap the service
if (file_exists('bootstrap.php'))
{
    include_once 'bootstrap.php';
}

$config = [
    'settings' => [
        'displayErrorDetails' => true, 
    ],
];

$app = new \Slim\App($config);
$app->add(new RegistrationRequestAuthorizer());

// Service Routes
// $app->get('/{id}', GetGameAction::class); 
// $app->get('/schedule/{teamid}/season/{seasonid}', GetTeamScheduleAction::class);
// $app->put('/{id}', PutGameAction::class);
// $app->post('/', PostGameAction::class);
// $app->delete('/{id}', DeleteGameAction::class);

$app->get('/{id}', GetRegistrationAction::class);
$app->get('/download/{seasonid}', DownloadRegistrationAction::class);
                            
$app->run();
                    
                    
                    