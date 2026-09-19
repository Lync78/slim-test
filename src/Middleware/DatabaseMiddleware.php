<?php

namespace App\Middleware;

use App\Database\Database;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class DatabaseMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Database $database
    ) {
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        try {
            $this->database->getConnection();

            return $handler->handle($request);

        } catch (\PDOException $e) {
            $response = new Response();

            $response->getBody()->write(json_encode(['error' => 'Database connection failed']));

            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }
}
