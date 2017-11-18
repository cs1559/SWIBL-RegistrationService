<?php

/**
 * This is the main contorller for the GameService.
 */



use swibl\services\registration\actions\ConfirmRegistrationAction;
use swibl\services\registration\actions\DownloadRegistrationAction;
use swibl\services\registration\actions\GetAllRegistrationAction;
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

$c = new \Slim\Container(); //Create Your container

//Override the default Not Found Handler
$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
        ->withStatus(404)
        ->withHeader('Content-Type', 'text/html')
        ->write('SWIBL API - Page not found');
    };
};

// $config = [
//     'settings' => [
//         'displayErrorDetails' => true, 
//     ],
// ];

$app = new \Slim\App($c);
$app->add(new RegistrationRequestAuthorizer());

// Service Routes
$app->get('/{id}', GetRegistrationAction::class);
$app->get('/download/{seasonid}', DownloadRegistrationAction::class);
$app->get('/download/season/{seasonid}', DownloadRegistrationAction::class);
$app->get('/season/{seasonid}', GetAllRegistrationAction::class);
$app->put('/{id}/confirm/{confnum}', ConfirmRegistrationAction::class);
$app->post('/', PostRegistrationAction::class);
$app->delete('/{id}', DeleteRegistrationAction::class);
$app->put('/{id}', PutRegistrationAction::class);
            
$app->run();
                    
                    
                    