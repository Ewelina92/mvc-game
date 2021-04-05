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
 * Class DiceHand.
 */
class DiceHand
{
    private $dices;
    // private $values;
    private int $sum;
    private $graphicClasses;

    public function __construct(int $dices = 2)
    {
        $this->dices = [];
        $this->graphicClasses = [];
        // $this->values = [];

        for ($i = 0; $i < $dices; $i++) {
            $this->dices[$i] = new GraphicalDice();
        }
    }

    public function roll(): void
    {
        $len = count($this->dices);
        $this->sum = 0;

        for ($i = 0; $i < $len; $i++) {
            // $value = dices[$i]->roll();
            // $values[i] = $value;

            // $this->sum += $value;
            $this->sum += $this->dices[$i]->roll();
        }
    }

    public function getSum()
    {
        return $this->sum;
    }

    public function getLastRoll(): string
    {
        $len = count($this->dices);

        $res = "";
        for ($i = 0; $i < $len; $i++) {
            $res .= $this->dices[$i]->getLastRoll() . ", ";
        }

        return $res . " = " . $this->sum;
    }

    public function graphicLastRoll(): array
    {
        $len = count($this->dices);

        // $res = "";
        for ($i = 0; $i < $len; $i++) {
            $this->graphicClasses[$i] = $this->dices[$i]->graphic();
            // $res .= $this->dices[$i]->graphic() . ", ";
        }

        return $this->graphicClasses;
    }
}
