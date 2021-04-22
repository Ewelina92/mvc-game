<?php

declare(strict_types=1);

namespace Eaja20\Yatzy;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the YatzyHandler class.
 */
class YatzyHandlerTest extends TestCase
{

    /**
     * Test the function welcome
     * @runInSeparateProcess
     * 
     */
    public function testWelcome()
    {   
        session_start();
        $game = new YatzyHandler();

        $_SESSION = [];
        $_POST = [];

        $result = $game->playGame();

        $expected = [
            "header" => "Yatzy",
            "pageToRender" => "layout/yatzy-layout/yatzy-welcome.php"
        ];

        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }

    /**
     * Test the function startGame.
     * @runInSeparateProcess
     * 
     */
    public function testStartGame()
    {   
        session_start();
        $game = new YatzyHandler();

        $_SESSION = [];
        $_POST = [
            "startYatzy" => "start"
        ];

        $result = $game->playGame();

        $expected = [
            "header" => "Roll number: 1",
            "pageToRender" => "layout/yatzy-layout/yatzy-round.php"
        ];

        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }

    /**
     * Test the continuing game after first round.
     * @runInSeparateProcess
     * 
     */
    public function testContinueGame()
    {   
        session_start();
        $game = new YatzyHandler();

        $_SESSION = [
            "diceToRollYatzy" => 5,
            "yatzyGame" => "playing",
            "turnNumberYatzy" => 1,
            "savedDiceValuesYatzy" => [],
            "allValuesFromTurn" => [],
            "resultSlotsYatzy" => [
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
                6 => null,
            ]
        ];
        $_POST = [
            "rollAgain" => "roll",
            "ydice1" => "6",
        ];

        $result = $game->playGame();

        $expected = [
            "header" => "Roll number: 2",
            "pageToRender" => "layout/yatzy-layout/yatzy-round.php"
        ];

        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }

    /**
     * Test the response when all dice are saved
     * @runInSeparateProcess
     * 
     */
    public function testAllDiceSaved()
    {   
        session_start();
        $game = new YatzyHandler();

        $_SESSION = [
            "diceToRollYatzy" => 5,
            "yatzyGame" => "playing",
            "turnNumberYatzy" => 1,
            "savedDiceValuesYatzy" => [],
            "allValuesFromTurn" => [6,6,6,6,6],
            "resultSlotsYatzy" => [
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
                6 => null,
            ]
        ];
        $_POST = [
            "rollAgain" => "roll",
            "ydice1" => "6",
            "ydice2" => "6",
            "ydice3" => "6",
            "ydice4" => "6",
            "ydice5" => "6",
        ];

        $result = $game->playGame();

        $expected = [
            "header" => "Place your points",
            "pageToRender" => "layout/yatzy-layout/yatzy-points.php"
        ];

        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }

    /**
     * Test the response when no available slot
     * @runInSeparateProcess
     * 
     */
    public function testAllDiceSavedNoAvailable()
    {   
        session_start();
        $game = new YatzyHandler();

        $_SESSION = [
            "diceToRollYatzy" => 5,
            "yatzyGame" => "playing",
            "turnNumberYatzy" => 1,
            "savedDiceValuesYatzy" => [],
            "allValuesFromTurn" => [6,6,6,6,6],
            "resultSlotsYatzy" => [
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
                6 => 12,
            ]
        ];
        $_POST = [
            "rollAgain" => "roll",
            "ydice1" => "6",
            "ydice2" => "6",
            "ydice3" => "6",
            "ydice4" => "6",
            "ydice5" => "6",
        ];

        $result = $game->playGame();

        $expected = [
            "header" => "Place your points",
            "pageToRender" => "layout/yatzy-layout/yatzy-points.php"
        ];

        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }


    /**
     * Test the assigning of point
     * @runInSeparateProcess
     * 
     */
    public function testAssignPoints()
    {   
        session_start();
        $game = new YatzyHandler();

        $_SESSION = [
            "diceToRollYatzy" => 5,
            "yatzyGame" => "playing",
            "turnNumberYatzy" => 1,
            "savedDiceValuesYatzy" => [],
            "allValuesFromTurn" => [6,6,6,6,6],
            "resultSlotsYatzy" => [
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
                6 => 12,
            ]
        ];
        $_POST = [
            "assignPoints" => "assign",
            "choice" => "2:25"
        ];

        $result = $game->playGame();

        $expected = [
            "header" => "Roll number: 1",
            "pageToRender" => "layout/yatzy-layout/yatzy-round.php"
        ];

        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }

    /**
     * Test the assigning of points the last time.
     * @runInSeparateProcess
     * 
     */
    public function testAssignPointsFinal()
    {   
        session_start();
        $game = new YatzyHandler();

        $_SESSION = [
            "diceToRollYatzy" => 5,
            "yatzyGame" => "playing",
            "turnNumberYatzy" => 1,
            "savedDiceValuesYatzy" => [],
            "allValuesFromTurn" => [6,6,6,6,6],
            "resultSlotsYatzy" => [
                1 => 5,
                2 => 10,
                3 => 15,
                4 => 20,
                5 => null,
                6 => 30,
            ]
        ];
        $_POST = [
            "assignPoints" => "assign",
            "choice" => "5:25"
        ];

        $result = $game->playGame();

        $expected = [
            "header" => "Thank you for playing Yatzy!",
            "pageToRender" => "layout/yatzy-layout/yatzy-ending.php"
        ];

        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }


    /**
     * Test the response when continuing after the third throw.
     * @runInSeparateProcess
     * 
     */
    public function testContinueAfterThirdTurn()
    {   
        session_start();
        $game = new YatzyHandler();

        $_SESSION = [
            "diceToRollYatzy" => 5,
            "yatzyGame" => "playing",
            "turnNumberYatzy" => 3,
            "savedDiceValuesYatzy" => [],
            "allValuesFromTurn" => [6,6,6,6,6],
            "resultSlotsYatzy" => [
                1 => 5,
                2 => 10,
                3 => 15,
                4 => 20,
                5 => null,
                6 => 30,
            ]
        ];
        $_POST = [
            "checkTurnResult" => "test",
        ];

        $result = $game->playGame();

        $expected = [
            "header" => "Place your points",
            "pageToRender" => "layout/yatzy-layout/yatzy-points.php"
        ];

        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }






    


}