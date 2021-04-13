<?php

/**
 * Load the routes into the router, this file is included from
 * `htdocs/index.php` during the bootstrapping to prepare for the request to
 * be handled.
 */

declare(strict_types=1);

use FastRoute\RouteCollector;

$router = $router ?? null;

$router->addRoute("GET", "/test", function () {
    // A quick and dirty way to test the router or the request.
    return "Testing response";
});

$router->addRoute("GET", "/", "\Eaja20\Controller\Index");
$router->addRoute("GET", "/debug", "\Eaja20\Controller\Debug");
$router->addRoute("GET", "/twig", "\Eaja20\Controller\TwigView");

$router->addGroup("/dice", function (RouteCollector $router) {
    $router->addRoute("GET", "", ["\Eaja20\Controller\Game21", "index"]);
    $router->addRoute("GET", "?reset=True", ["\Eaja20\Controller\Game21", "index"]);
    $router->addRoute("GET", "?turn=computer", ["\Eaja20\Controller\Game21", "index"]);
    $router->addRoute("POST", "", ["\Eaja20\Controller\Game21", "index"]);
});

$router->addGroup("/yatzy", function (RouteCollector $router) {
    $router->addRoute("GET", "", ["\Eaja20\Controller\Yatzy", "index"]);
    $router->addRoute("POST", "", ["\Eaja20\Controller\Yatzy", "index"]);
});

$router->addGroup("/session", function (RouteCollector $router) {
    $router->addRoute("GET", "", ["\Eaja20\Controller\Session", "index"]);
    $router->addRoute("GET", "/destroy", ["\Eaja20\Controller\Session", "destroy"]);
});

$router->addGroup("/some", function (RouteCollector $router) {
    $router->addRoute("GET", "/where", ["\Eaja20\Controller\Sample", "where"]);
});

$router->addGroup("/form", function (RouteCollector $router) {
    $router->addRoute("GET", "/view", ["\Eaja20\Controller\Form", "view"]);
    $router->addRoute("POST", "/process", ["\Eaja20\Controller\Form", "process"]);
});
