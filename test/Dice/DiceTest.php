<?php

declare(strict_types=1);

namespace Eaja20\Dice;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the Dice class.
 */
class DiceTest extends TestCase
{
    /**
     * Try to create the Dice class.
     */
    public function testCreateTheDiceClass()
    {
        $dice = new Dice();
        $this->assertInstanceOf("\Eaja20\Dice\Dice", $dice);
    }

    /**
     * Test the function roll().
     */
    public function testRoll()
    {
        $dice = new Dice(6); // faces, roll
        $res = $dice->roll();

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