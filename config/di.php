<?php

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use function DI\autowire;
use function DI\get;

return array(
    FilesystemLoader::class => autowire()
        ->constructorParameter('paths', 'templates'),

    Environment::class => autowire()
        ->constructorParameter('loader', get(FilesystemLoader::class))
);