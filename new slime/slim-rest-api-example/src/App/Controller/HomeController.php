<?php

namespace App\Controller;

use Psr\Container\ContainerInterface;

class HomeController
{

    public function index($request, $response, $args)
    {
        return $response->write("Hello, world!");
    }
}
