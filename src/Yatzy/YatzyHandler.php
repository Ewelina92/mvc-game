<?php

declare(strict_types=1);

namespace Eaja20\Yatzy;

use Eaja20\Dice\{
    Dice,
    GraphicalDice,
    DiceHand
};
use Eaja20\GameInterface\GameHandlerInterface;

use function Eaja20\Functions\{
    destroySession
};

/**
 * Class YatzyHandler.
 */
class YatzyHandler implements GameHandlerInterface
{
    /**
     * Function that welcomes the user to the game when no game in progress.
     *
     * @return array
     */
    private function welcome(): array
    {
        return [
            "title" => "Yatzy",
            "header" => "Yatzy",
            "message" => ("Welcome to Yatzy!
                You play with 5 dice, and each turn you can roll maximum 3 times.
                The goal is to to get as many points as possible to fill the slots of
                1, 2, 3, 4, 5 and 6. If you can't fill a slot, you're forced to fill one
                with zero points. A sum of 63 or more gives 50 extra points as a bonus."),
            "pageToRender" => "layout/yatzy-layout/yatzy-welcome.php"
        ];
    }

    /**
     * Function that handles logic for each turn.
     *
     * @return array
     */
    private function doTurn(): array
    {
        $data = [
            "title" => "Yatzy",
            "header" => "Roll number: " . $_SESSION["turnNumberYatzy"],
            "message" => "You rolled: ",
            "savedDiceValues" => null,
            "amountOfDice" => 5,
            "pageToRender" => "layout/yatzy-layout/yatzy-round.php",
            "turn" => $_SESSION["turnNumberYatzy"],
            "slot1" => $_SESSION["resultSlotsYatzy"][1],
            "slot2" => $_SESSION["resultSlotsYatzy"][2],
            "slot3" => $_SESSION["resultSlotsYatzy"][3],
            "slot4" => $_SESSION["resultSlotsYatzy"][4],
            "slot5" => $_SESSION["resultSlotsYatzy"][5],
            "slot6" => $_SESSION["resultSlotsYatzy"][6],
        ];

        $diceHand = new DiceHand();

        for ($i = 0; $i < $_SESSION["diceToRollYatzy"]; $i++) {
            $diceHand->addDice(new GraphicalDice());
        }

        $diceHand->roll(); // roll dice

        if (isset($_SESSION["savedDiceValuesYatzy"])) {
            foreach ($_SESSION["savedDiceValuesYatzy"] as $value) {
                $diceHand->addDice(new GraphicalDice($value));
            }
        }

        // reset
        $_SESSION["diceToRollYatzy"] = 5;
        $_SESSION["savedDiceValuesYatzy"] = [];

        // display the result of the roll
        $data["diceHandRoll"] = "";
        // graphic representation of the dice
        for ($i = 0; $i < 5; $i++) {
            $data["diceHandRoll"] .= "<span class=\"{$diceHand->graphicLastRoll()[$i]}\" ></span>";
        }

        $diceValues = $diceHand->getDiceValues();
        $_SESSION["allValuesFromTurn"] = $diceValues;

        $diceValueCount = count($diceValues);

        for ($i = 0; $i < $diceValueCount; $i++) {
            $data["diceValue" . $i] = $diceValues[$i];
        }

        return $data;
    }

    /**
     * Function that handles the result of one roll.
     *
     * @return array
     */
    private function checkRoll(): array
    {
        $_SESSION["turnNumberYatzy"] += 1;

        // check if any die should be saved and save values
        for ($i = 1; $i < 6; $i++) {
            if (isset($_POST["ydice" . $i])) {
                array_push($_SESSION["savedDiceValuesYatzy"], intval($_POST["ydice" . $i]));
                // one fewer die to roll next turn
                $_SESSION["diceToRollYatzy"] -= 1;
            }
        }

        // skip rolls if all already saved
        if (count($_SESSION["savedDiceValuesYatzy"]) === 5) {
            return $this->getAvailableSlots();
        }

        return $this->doTurn();
    }

    /**
     * Function that returns available alternatives for placing points after a roll.
     *
     * @return array
     */
    private function getAvailableSlots(): array
    {
        $availableSlots = [];

        // check values from turn and check if corresponding slot is available
        foreach ($_SESSION["allValuesFromTurn"] as $value) {
            if ($_SESSION["resultSlotsYatzy"][$value] ===  null) {
                // get possible combinations as key/value pairs
                if (!isset($availableSlots[$value])) {
                    $availableSlots[$value] = $value;
                    continue;
                }
                $availableSlots[$value] += $value;
            }
        }

        // if none of the dice match a free slot
        if (count($availableSlots) === 0) {
            foreach ($_SESSION["resultSlotsYatzy"] as $key => $value) {
                if ($value === null) {
                    // get possible combinations as key/value pairs
                    $availableSlots[$key] = 0;
                }
            }
        }

        return [
            "title" => "Yatzy",
            "header" => "Place your points",
            "message" => "These are the available options:",
            "pageToRender" => "layout/yatzy-layout/yatzy-points.php",
            "options" => $availableSlots,
            "slot1" => $_SESSION["resultSlotsYatzy"][1],
            "slot2" => $_SESSION["resultSlotsYatzy"][2],
            "slot3" => $_SESSION["resultSlotsYatzy"][3],
            "slot4" => $_SESSION["resultSlotsYatzy"][4],
            "slot5" => $_SESSION["resultSlotsYatzy"][5],
            "slot6" => $_SESSION["resultSlotsYatzy"][6]
        ];
    }

    /**
     * Function that handles logic when assigning points.
     *
     * @return array
     */
    private function assignPoints(): array
    {
        $keyVal = explode(":", $_POST["choice"]);
        $_SESSION["resultSlotsYatzy"][intval($keyVal[0])] = intval($keyVal[1]);

        // check if end of game
        foreach ($_SESSION["resultSlotsYatzy"] as $value) {
            if ($value === null) {
                $_SESSION["diceToRollYatzy"] = 5; // set dice to 5 again
                $_SESSION["turnNumberYatzy"] = 1; // set to first turn again
                $_SESSION["savedDiceValuesYatzy"] = []; // set to no saved dice
                return $this->doTurn(); // start a new turn
            }
        }
        return $this->checkBonus();
    }

    /**
     * Function that check if bonus points should be applied.
     *
     * @return array
     */
    private function checkBonus(): array
    {
        $_SESSION["sum"] = array_sum($_SESSION["resultSlotsYatzy"]);
        $_SESSION["bonus"] = 0;

        if ($_SESSION["sum"] >= 63) {
            $_SESSION["bonus"] = 50;
        }

        $totalScore = $_SESSION["sum"] + $_SESSION["bonus"];
        return $this->showEnding($totalScore);
    }

    /**
     * Function that shows the end result of the game.
     *
     * @return array
     */
    private function showEnding(int $totalScore): array
    {
        $data = [
            "title" => "Yatzy",
            "header" => "Thank you for playing Yatzy!",
            "message" => ("Your sum is: " . $_SESSION["sum"] . "<br>Your bonus is: " . $_SESSION["bonus"] .
            "<br>So your final score is: " . $totalScore),
            "pageToRender" => "layout/yatzy-layout/yatzy-ending.php"
        ];

        destroySession();

        return $data;
    }

    /**
     * Function that sets up necessary information in SESSION.
     *
     * @return array
     */
    private function setupGame(): array
    {
        $_SESSION = [
            "diceToRollYatzy" => 5,
            "yatzyGame" => "playing",
            "turnNumberYatzy" => 1,
            "resultSlotsYatzy" => [
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
                6 => null
            ]
        ];

        return $this->doTurn();
    }

    /**
     * Function that handles main game play.
     *
     * @return array
     */
    public function playGame(): array
    {
        // user clicked continue after assigning points
        if (isset($_POST["assignPoints"])) {
            return $this->assignPoints();
        }

        // user clicked continue after third turn
        if (isset($_POST["checkTurnResult"])) {
            return $this->getAvailableSlots();
        }

        // user clicked start game in welcome()
        if (isset($_POST["startYatzy"])) {
            return $this->setupGame();
        }

        // user clicked continue after first or second turn
        if (isset($_POST["rollAgain"])) {
            return $this->checkRoll();
        }

        // no game in progress, show welcome
        return $this->welcome();
    }
}
