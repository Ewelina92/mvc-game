<?php

declare(strict_types=1);

namespace Eaja20\Dice;

// use function Mos\Functions\{
//     destroySession,
//     redirectTo,
//     renderView,
//     renderTwigView,
//     sendResponse,
//     url
// };

/**
 * Class Dice.
 */
class Dice
{
    private $faces;
    protected $roll;
    //private ?int $roll = null;

    public function __construct(int $faces = 6)
    {
        $this->faces = $faces;
        $this->roll = null;
    }

    public function roll(): int
    {
        $this->roll = rand(1, $this->faces);

        return $this->roll;
    }

    public function getLastRoll(): int
    {
        return $this->roll;
    }
}
