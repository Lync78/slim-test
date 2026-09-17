<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\Controller\MainController;
use Dotenv\Dotenv;
use App\Middleware\DatabaseMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

require __DIR__ . '/../migrations/migrate.php';

$database = new Database();

$app = AppFactory::create();

$app->add(new DatabaseMiddleware($database));

$app->get('/api/test', [MainController::class,'main']);

$app->post("/api/register", [MainController::class, 'register']);

$app->run();