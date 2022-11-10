<?php

use Blog\Database;
use Blog\LatestPosts;
use Blog\Slim\TwigMiddleware;
use DevCoder\DotEnv;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Blog\PostMapper;

require __DIR__ . '/vendor/autoload.php'; // запит на виконання лоадеру

// $loader = new FilesystemLoader('templates'); // підключення шаблонів твіг
// $view = new Environment($loader);

$builder = new \DI\ContainerBuilder(); // використання php-di
$builder->addDefinitions('config/di.php');
(new DotEnv(__DIR__ . '/.env'))->load(); // використання бібліотеки php-dotenv (підтримка .енв файлів)

$container = $builder->build();

AppFactory::setContainer($container); // що б слім бачив контейнери

// Create app
$app = AppFactory::create();

$view = $container->get(Environment::class);
$app->add(new TwigMiddleware($view)); // відмалювання помилок при завантаженні

$connection = $container->get(Database::class)->getConnection(); // передача запиту на підключення в PDO

$app->get('/site', function (Request $request, Response $response) use ($view, $connection) { // старт пейдж
    $latestPosts = new LatestPosts($connection);
    $posts = $latestPosts->get(3); // макс кіл-ть постів на 1 ст.

    $body = $view->render('index.twig', [
        'posts' => $posts
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/history', function (Request $request, Response $response) use ($view) { // ебаут пейдж
    $body = $view->render('istoriya.twig', [
        'name' => 'шановний користувач'
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/about', function (Request $request, Response $response) use ($view) { // ебаут пейдж
    $body = $view->render('about.twig', [
        'name' => 'шановний користувач'
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/blog[/{page}]' /* це опціональний параметр, спрацьовує внутрішній патерн*/, function (Request $request, Response $response, $args) use ($view, $connection) {
    // блог (пагінація) пейдж
    $postMapper = new PostMapper($connection);

    $page = isset($args['page']) ? (int) $args['page'] : 1;
    $limit = 3; // макс кіл-ть постів на 1 ст

    $posts = $postMapper->getList($page, $limit, 'DESC'); // сортування постів за датою (часом)

    $totalcount = $postMapper->getTotalCount(); // загальна кіл-ть сторінок для пагінації
    $body = $view->render('blog.twig', [
        'posts' => $posts,
        'pagination' => [ // реалізація роботи пагінації
            'current' => $page,// конкретна сторінка
            'paging' => ceil( $totalcount / $limit) // функція сеіл робить округлення
        ]
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/{url_key}', function (Request $request, Response $response, $args) use ($view, $connection) { // оформлення читабельних ЮРЛ
    $postMapper = new PostMapper($connection);
    $post = $postMapper->getByUrlKey((string) $args['url_key']); // підключення класу постмапер, який відповідає за завантаження контенту БД

    if (empty($post)) {  // відмалювання шаблону помилки
        $body = $view->render('not-found.twig');
    } else {
        $body = $view->render('post.twig', [
            'post' => $post
        ]);
    }
    $response->getBody()->write($body);
    return $response;
});

$app->run();