<?php

declare(strict_types=1);

namespace Eaja20\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Eaja20\Dice\Game;

use function Eaja20\Functions\renderView;

/**
 * Controller for the dice route.
 */
class Game21
{
    public function index(): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        $callable = new Game();
        $data = $callable->playGame();

        $body = renderView($data["pageToRender"], $data);

        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }
}
