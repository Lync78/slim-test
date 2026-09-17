<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\Controller\MainController;
use Dotenv\Dotenv;
use App\Middleware\DatabaseMiddleware;
use App\Database\Database;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

require __DIR__ . '/../migrations/migrate.php';

$database = new Database();

$app = AppFactory::create();

$app->add(new DatabaseMiddleware($database));

$app->addBodyParsingMiddleware();

$app->get('/api/test', [MainController::class,'main']);

$app->post("/api/register", [MainController::class, 'register']);

$app->get('/docs', function ($request, $response) {
    $html = file_get_contents(__DIR__ . '/../docs/index.html');

    $response->getBody()->write($html);

    return $response
        ->withHeader('Content-Type', 'text/html');
});

$app->get('/docs/openapi.yaml', function ($request, $response) {
    $yaml = file_get_contents(__DIR__ . '/../docs/openapi.yaml');

    $response->getBody()->write($yaml);

    return $response
        ->withHeader('Content-Type', 'application/yaml');
});

$app->run();