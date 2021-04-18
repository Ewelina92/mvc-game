<?php

declare(strict_types=1);

namespace Eaja20\Dice;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the controller Debug.
 */
class DiceTest extends TestCase
{
    /**
     * Try to create the Dice class.
     */
    public function testCreateTheDiceClass()
    {
        $controller = new Dice();
        $this->assertInstanceOf("\Eaja20\Dice\Dice", $controller);
    }

    /**
     * Test the function roll().
     */
    public function testRoll()
    {
        $controller = new Dice(6); // faces, roll
        $res = $controller->roll();

        $resultSpan = boolval($res <= 6 && $res >=1);

        $this->assertIsInt($res);
        $this->assertTrue($resultSpan);
    }

    /**
     * Test the function getLastRoll().
     */
    public function testGetLastRoll()
    {
        $controller = new Dice(6, 4); // faces, roll
        $res = $controller->getLastRoll();
        $exp = 4;

        $this->assertEquals($exp, $res);
    }
}