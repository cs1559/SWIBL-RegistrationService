<?php

/**
 * This is the main contorller for the GameService.
 */



use swibl\services\registration\actions\ConfirmRegistrationAction;
use swibl\services\registration\actions\DownloadRegistrationAction;
use swibl\services\registration\actions\GetRegistrationAction;
use swibl\services\registration\actions\PostRegistrationAction;
use swibl\services\registration\RegistrationRequestAuthorizer;
use swibl\services\registration\actions\DeleteRegistrationAction;
use swibl\services\registration\actions\PutRegistrationAction;

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
$app->get('/{id}', GetRegistrationAction::class);
$app->get('/download/{seasonid}', DownloadRegistrationAction::class);
$app->put('/{id}/confirm/{confnum}', ConfirmRegistrationAction::class);
$app->post('/', PostRegistrationAction::class);
$app->post('/', DeleteRegistrationAction::class);
$app->put('/{id}', PutRegistrationAction::class);
            
$app->run();
                    
                    
                    