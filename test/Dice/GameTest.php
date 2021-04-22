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
     * Test the function resetGame() in playGame().
     * @runInSeparateProcess
     */
    public function testResetGame()
    {   
        session_start();
        $game = new Game();
        $_GET["reset"] = "True";

        $_SESSION = [
            "playerBitcoin" => 20,
        ];
        
        $result = $game->playGame(); 

        $expected = [
            "title" => "21",
            "header" => "Game 21",
            "message" => ("Welcome to the dice game 21! 
                You can maximum bet half of your bitcoins."),
            "playerBitcoin" => 10,
            "pageToRender" => "layout/dice-welcome.php"
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * Test the function startGame() in playGame() when invalid bet first round.
     * 
     */
    public function testStartGameInvalidBet()
    {   
        $game = new Game();
        $_POST['dice'] = 1;
        $_POST['bitcoin'] = "6"; // invalid bet

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
     * Test the function startGame() in playGame() when valid bet.
     * @runInSeparateProcess
     * 
     */
    public function testStartGameValidBet()
    {   
        session_start();

        $mockDiceHand = $this->createMock(DiceHand::class);
        $mockDiceHand->method('getSum')
             ->willReturn(10);
        $mockDiceHand->method('graphicLastRoll')
             ->willReturn(["die die-5", "die die-5"]);

        $game = new Game($mockDiceHand);

        $_SESSION = [
            "rounds" => 1,
            "playerBitcoin" => 10,
            "computerBitcoin" => 100,
        ];

        $_POST = [
            "bitcoin" => "5", // valid bet
            "dice" => "1"
        ];


        $result = $game->playGame();

        $expected = [
            "title" => "21",
            "header" => "Player's round",
            "pageToRender" => "layout/dice.php"
        ];

        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }

    /**
     * Test the response when player gets exactly 21.
     * @runInSeparateProcess
     * 
     */
    public function testPlayerExactly21()
    {   
        session_start();

        $mockDiceHand = $this->createMock(DiceHand::class);
        $mockDiceHand->method('getSum')
             ->willReturn(10);
        $mockDiceHand->method('graphicLastRoll')
             ->willReturn(["die die-5", "die die-5"]);

        $game = new Game($mockDiceHand);

        $_SESSION = [
            "rounds" => 2,
            "playerBitcoin" => 10,
            "computerBitcoin" => 100,
            "currentBet" => 0,
            "playerScore" => 11,
            "computerScore" => 0,
            "numDice" => 2,
        ];

        $_POST = [];

        $result = $game->playGame();

        $expected = [
            "title" => "21",
            "header" => "Congratulations!",
            "pageToRender" => "layout/dice-between.php"
        ];

        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }

    /**
     * Test the response when player gets over 21.
     * @runInSeparateProcess
     * 
     */
    public function testPlayerOver21()
    {
        session_start();

        $mockDiceHand = $this->createMock(DiceHand::class);
        $mockDiceHand->method('getSum')
             ->willReturn(10);
        $mockDiceHand->method('graphicLastRoll')
             ->willReturn(["die die-5", "die die-5"]);

        $game = new Game($mockDiceHand);

        $_SESSION = [
            "playerBitcoin" => 10,
            "computerBitcoin" => 100,
            "currentBet" => 0,
            "playerScore" => 15,
            "computerScore" => 0,
            "numDice" => 2,
        ];

        $_POST = [];

        $result = $game->playGame();

        $expected = [
            "title" => "21",
            "header" => "You lost!",
            "pageToRender" => "layout/dice-winner.php"
        ];

        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }

    /**
     * Test the response when the computer wins.
     * @runInSeparateProcess
     * 
     */
    public function testComputerWin()
    {
        session_start();

        $mockDiceHand = $this->createMock(DiceHand::class);
        $mockDiceHand->method('getSum')
             ->willReturn(21);
        $mockDiceHand->method('graphicLastRoll')
             ->willReturn(["die die-5", "die die-5"]);

        $game = new Game($mockDiceHand);

        $_SESSION = [
            "playerBitcoin" => 10,
            "computerBitcoin" => 100,
            "currentBet" => 0,
            "playerScore" => 15,
            "numDice" => 2,
        ];

        $_POST = [];

        $_GET = [
            "turn" => "computer",
        ];

        $result = $game->playGame();

        $expected = [
            "title" => "21",
            "header" => "Result this round",
            "pageToRender" => "layout/dice-winner.php"
        ];

        $this->assertEquals(1, $_SESSION["computerWins"]);
        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }

    /**
     * est the response when the computer loses.
     * @runInSeparateProcess
     * 
     */
    public function testComputerLose()
    {
        session_start();

        $mockDiceHand = $this->createMock(DiceHand::class);
        $mockDiceHand->method('getSum')->willReturn(25);
        $mockDiceHand->method('graphicLastRoll')->willReturn(["die die-5", "die die-5"]);

        $game = new Game($mockDiceHand);

        $_SESSION = [
            "rounds" => 1 ,
            "computerWins" => 0,
            "playerWins" => 0,
            "playerBitcoin" => 10,
            "computerBitcoin" => 100,
            "currentBet" => 0,
            "playerScore" => 15,
            "numDice" => 2,
        ];

        $_POST = [];

        $_GET = [
            "turn" => "computer",
        ];

        $result = $game->playGame();

        $expected = [
            "title" => "21",
            "header" => "Result this round",
            "pageToRender" => "layout/dice-winner.php"
        ];

        $this->assertEquals(1, $_SESSION["playerWins"]);
        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }

    /**
     * Test the function startGame() in playGame() when invalid bet, not first round.
     * @runInSeparateProcess
     * 
     */
    public function testStartGameInvalidBetAgain()
    {   
        session_start();
        $game = new Game();

        $_SESSION = [
            "rounds" => 2,
            "playerWins" => 1,
            "computerWins" => 0,
            "playerBitcoin" => 10,
            "computerBitcoin" => 100,
        ];

        $_POST = [
            "nextRound" => "next round",
            "bitcoin" => "6", // invalid bet
        ];


        $result = $game->playGame();

        $expected = [
            "title" => "21",
            "header" => "Invalid bet!",
            "pageToRender" => "layout/dice-winner.php"
        ];

        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }

}