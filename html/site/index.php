<?php
/* slim work */
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

/*connect twig templates*/
$loader = new \Twig\Loader\FilesystemLoader('templates');
$view = new \Twig\Environment($loader);

// Create app
$app = AppFactory::create();

/* routes for url */
$app->get('/', function (Request $request, Response $response, $args) use ($view) { /*logic for twig*/
    $body = $view->render('index.twig');
    $response->getBody()->write($body);
    return $response;
});

$app->get('/about', function (Request $request, Response $response, $args) use ($view) {
    $body = $view->render('about.twig', [
      'name' => 'Yurii'
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->run();