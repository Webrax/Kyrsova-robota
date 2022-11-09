<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Blog\PostMapper;

require __DIR__ . '/vendor/autoload.php'; // запит на виконання лоадеру

$loader = new \Twig\Loader\FilesystemLoader('templates'); // підключення шаблонів твіг
$view = new \Twig\Environment($loader);


$config = include 'config/database.php'; // логіка БД
$dsn = $config['dsn'];
$username = $config['username'];
$password = $config['password'];

try {  // конект БД, написання повідомлення про ерор
    $connection = new PDO($dsn, $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $exception) {
    echo 'Database error: ' .$exception->getMessage();
    die();
}

/* Create app */
$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true); // відмалювання помилок при завантаженні


$app->get('/site', function (Request $request, Response $response) use ($view, $connection) { // хоум пейдж
    $latestPosts = new \Blog\LatestPosts($connection);
    $posts = $latestPosts->get(3); // сортування постів за датою (часом)

    $body = $view->render('index.twig', [
        'posts' => $posts
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/about', function (Request $request, Response $response, $args) use ($view) { // ебаут пейдж
    $body = $view->render('about.twig', [
        'name' => 'користувач'
        ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/{url_key}', function (Request $request, Response $response, $args) use ($view, $connection)  { // оформлення читабельних ЮРЛ
    $postMapper = new PostMapper($connection); // підключення класу постмапер, який відповідає за завантаження контенту БД

    $post = $postMapper->getByUrlKey((string) $args['url_key']);

    if (empty($post)) { // відмалювання шаблону помилки
        $body = $view->render('not-found.twig');
    } else { // конект постмаппер класу
        $body = $view->render('post.twig', [
            'post' => $post
        ]);
    }
    $response->getBody()->write($body);
    return $response;
});

$app->run();