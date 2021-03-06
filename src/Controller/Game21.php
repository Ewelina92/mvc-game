<?php

declare(strict_types=1);

namespace Eaja20\Controller;

use Psr\Http\Message\ResponseInterface;
use Eaja20\Dice\Game;

use function Eaja20\Functions\renderView;

/**
 * Controller for the dice route.
 */
class Game21
{
    use ControllerTrait;

    public function index(): ResponseInterface
    {
        $callable = new Game();
        $data = $callable->playGame();

        $body = renderView($data["pageToRender"], $data);

        return $this->createResponse($body);
    }
}
