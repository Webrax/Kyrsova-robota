<?php

namespace Blog\Slim;

use Blog\Twig\AssetExtension;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;

class TwigMiddleware implements MiddlewareInterface // Мідл вейр це ПЗ, яке полегшує обмін даними між сервером і програмою
{
    private Environment $environment; //  обчислювальне оточення, необхідне для виконання програми

    public function __construct(Environment $environment) // сама констуркція цього обчислення
    {
        $this->environment = $environment;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface // відгук серверу на запит реквест
    {
     $this->environment->addExtension(new AssetExtension($request));
     return $handler->handle($request);
    }
}