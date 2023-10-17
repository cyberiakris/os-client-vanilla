<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    //$view =  new Slim\Views\PhpRenderer($settings['template_path']);
    $view = new Slim\Views\Twig($settings['template_path'], [
        'cache' => false, // Disable caching for development
        'debug' => true, // Enable debugging
    ]);

    // Define a 'base_url' global variable that's accessible in all templates
    $view->getEnvironment()->addGlobal('base_url', getenv('BASE_URL'));

    return $view;

};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};
