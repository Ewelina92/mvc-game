<?php

declare(strict_types=1);

namespace Eaja20\Dice;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the GraphicalDice class.
 */
class GraphicalDiceTest extends TestCase
{
    /**
     * Try to create the GraphicalDice class.
     */
    public function testCreateGraphicalDiceClass()
    {
        $graphicalDice = new GraphicalDice();
        $this->assertInstanceOf("\Eaja20\Dice\GraphicalDice", $graphicalDice);
    }

    /**
     * Test the function graphic().
     */
    public function testGraphicRoll()
    {
        $graphicalDice = new GraphicalDice(4); // roll
        $res = $graphicalDice->graphic();
        $exp = "die die-4";

        $this->assertEquals($exp, $res);
    }
}
