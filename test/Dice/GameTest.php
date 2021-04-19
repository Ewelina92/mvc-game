<?php

declare(strict_types=1);

namespace Eaja20\Dice;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the DiceHand class.
 */
class GameTest extends TestCase
{
    /**
     * Try to create the Dice class.
     */
    public function testCreateGameClass()
    {
        $game = new Game();
        $this->assertInstanceOf("\Eaja20\Dice\Game", $game);
    }

    /**
     * Test the function welcome() in playGame().
     * 
     */
    public function testWelcome()
    {   
        $game = new Game();

        $result = $game->playGame();

        $expected = [
            "title" => "21",
            "header" => "Game 21",
            "message" => ("Welcome to the dice game 21! 
                You can maximum bet half of your bitcoins."),
            "playerBitcoin" => 10,
            "pageToRender" => "layout/dice-welcome.php"
        ];

        //$this->assertIsInt($result);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test the function startGame() in playGame().
     * 
     */
    public function testStartGame()
    {   
        $game = new Game();
        $_POST['dice'] = 1;
        $_POST['bitcoin'] = "6"; // unvalid bet

        $result = $game->playGame();

        $expected = [
            "title" => "21",
            "header" => "Game 21",
            "message" => ("Welcome to the dice game 21! 
                You can maximum bet half of your bitcoins."),
            "playerBitcoin" => 10,
            "pageToRender" => "layout/dice-welcome.php"
        ];

        //$this->assertIsInt($result);
        $this->assertEquals($expected, $result);
    }

    // /**
    //  * Test the function roll().
    //  */
    // public function testRoll()
    // {
    //     $diceHand = new DiceHand();
    //     $die1 = new Dice(6, 2); // value = 2
    //     $die2 = new Dice(6, 3); // value = 3
    //     $die3 = new Dice(6, 4); // value = 4

    //     $diceHand->addDice($die1);
    //     $diceHand->addDice($die2);
    //     $diceHand->addDice($die3);

    //     $diceHand->roll();

    //     $result = $diceHand->getSum();

    //     $resultSpan = boolval($result <= 12 && $result >=2);

    //     $this->assertIsInt($result);
    //     $this->assertTrue($resultSpan);
    // }

    // /**
    //  * Test the function getLastRoll().
    //  */
    // public function testGetLastRoll()
    // {
    //     $diceHand = new DiceHand();
    //     $die1 = new Dice(6, 2); // value = 2
    //     $die2 = new Dice(6, 3); // value = 3
    //     $die3 = new Dice(6, 4); // value = 4

    //     $diceHand->addDice($die1);
    //     $diceHand->addDice($die2);
    //     $diceHand->addDice($die3);

    //     $res = $diceHand->getLastRoll();
    //     $exp = "2, 3, 4,  = 9";

    //     $this->assertEquals($exp, $res);
    // }

    // /**
    //  * Test the function graphicLastRoll().
    //  */
    // public function testGraphicLastRoll()
    // {
    //     $diceHand = new DiceHand();
    //     $die1 = new GraphicalDice(2); // value = 2
    //     $die2 = new GraphicalDice(3); // value = 3
    //     $die3 = new GraphicalDice(4); // value = 4

    //     $diceHand->addDice($die1);
    //     $diceHand->addDice($die2);
    //     $diceHand->addDice($die3);

    //     $res = $diceHand->graphicLastRoll();
    //     $exp = ["die die-2", "die die-3", "die die-4"];

    //     $this->assertEquals($exp, $res);
    // }
}