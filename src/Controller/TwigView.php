<?php

declare(strict_types=1);

namespace Eaja20\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;

use function Eaja20\Functions\renderTwigView;

/**
 * Controller for showing how Twig views works.
 */
class TwigView
{
    public function __invoke(): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        $data = [
            "header" => "Twig page",
            "message" => "Hey, edit this to do it youreself!",
        ];

        $body = renderTwigView("index.html", $data);

        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }
}
