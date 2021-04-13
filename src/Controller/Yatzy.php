<?php

declare(strict_types=1);

namespace Eaja20\Controller;

use Psr\Http\Message\ResponseInterface;
use Eaja20\Yatzy\YatzyHandler;
use Eaja20\Dice\{
    Dice,
    DiceHand
};

use function Eaja20\Functions\renderView;

/**
 * Controller for the Yatzy route.
 */
class Yatzy
{
    use ControllerTrait;

    public function index(): ResponseInterface
    {
        $callable = new YatzyHandler();
        $data = $callable->playGame();
        $body = renderView($data["pageToRender"], $data);

        return $this->createResponse($body);
    }
}
