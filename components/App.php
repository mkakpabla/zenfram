<?php


namespace Components;

use Exception;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class App
 * @package Core
 */
class App implements RequestHandlerInterface
{

    /**
     * Liste des middleware
     * @var array
     */
    private $middlewares = [];
    /**
     * Indice du middleware en cours
     * @var int
     */
    private $index = 0;

    /**
     * App constructor.
     * @param array $middlewares
     */
    public function __construct(array $middlewares)
    {
        foreach ($middlewares as $middleware) {
            $this->middlewares[] = $middleware;
        }
    }

    /**
     * Handles a request and produces a response.
     * May call other collaborating code to generate the response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $middleware = $this->getMiddleware();
        if (is_null($middleware)) {
            throw new Exception();
        } elseif ($middleware instanceof MiddlewareInterface) {
            return $middleware->process($request, $this);
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @return Response
     * @throws Exception
     */
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        return $this->handle($request);
        /*
        $route = $this->router->match($request);
        if (!$route) {
            return new Response(404, [], '<h1>404 Not Found</h1>');
        } else {
            if (is_callable($route->getHandler())) {
                $response =  call_user_func_array($route->getHandler(), [$request]);
                return new Response(200, [], $response);
            } elseif (is_string($route->getHandler())) {
                $target = explode('#', $route->getHandler());
                $controller = new $target[0]();
                $action = $target[1];
                $response = $controller->$action($request);
                return new Response(200, [], $response);
            }
        }*/
    }

    /**
     * @return object
     */
    private function getMiddleware()
    {
        if (array_key_exists($this->index, $this->middlewares)) {
            $middleware = $this->middlewares[$this->index];
            $this->index++;
            return $middleware;
        }
        return null;
    }
}