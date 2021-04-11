<?php

declare(strict_types=1);

namespace Eaja20\Dice;

use Eaja20\Dice\{
    Dice,
    DiceHand
};

use function Eaja20\Functions\{
    destroySession,
    redirectTo,
    renderView,
    sendResponse,
    url
};

/**
 * Class Game.
 */
class Game
{
    private function welcome(): array
    {
        $data = [
            "title" => "21",
            "header" => "Game 21",
            "message" => ("Welcome to the dice game 21! 
                You can maximum bet half of your bitcoins."),
            "playerBitcoin" => $_SESSION["playerBitcoin"],
            "pageToRender" => "layout/dice-welcome.php"
        ];
        return $data;
    }

    private function playerTurn(): array
    {
        $data = [
            "title" => "21",
            "header" => "Player's round",
            "message" => "You threw:",
            "pageToRender" => "layout/dice.php"
        ];

        $diceHand = new DiceHand($_SESSION["numDice"]); // start game with 1-2 dice
        $diceHand->roll(); // roll the dice

        $data["diceHandRoll"] = "";
        // graphic representation of the dice
        for ($i = 0; $i < $_SESSION["numDice"]; $i++) {
            $data["diceHandRoll"] .= "<span class=\"{$diceHand->graphicLastRoll()[$i]}\" ></span>";
        }

        $data["roundSum"] = $diceHand->getSum(); // get sum of all rolls
        $_SESSION["playerScore"] += $data["roundSum"]; // add to total score
        $data["totalScorePlayer"] = $_SESSION["playerScore"]; // make total score available in $data

        // check if exactly 21: congrats
        if (intval($data["totalScorePlayer"]) == 21) {
            $data = [
                "title" => "21",
                "header" => "Congratulations!",
                "message" => "You got 21, now it's the computers turn.",
                "pageToRender" => "layout/dice-between.php"
            ];
            return $data;
        }

        // check if over 21: game over
        if (intval($data["totalScorePlayer"]) > 21) {
            $data = [
                "title" => "21",
                "header" => "You lost!",
                "message" => "You got over 21, the computer wins this round."
            ];

            return $this->showResult("computer", $data);
        }

        return $data;
    }

    private function computerTurn(): array
    {
        $data = [
            "title" => "21",
            "header" => "Result this round",
        ];

        $_SESSION["computerScore"] = 0;

        $diceHand = new DiceHand($_SESSION["numDice"]); // start game with 1-2 dice

        while ($_SESSION["computerScore"] < 21) {
            $diceHand->roll(); // roll the dice
            $data["roundSum"] = $diceHand->getSum(); // get the sum of the round
            $_SESSION["computerScore"] += $data["roundSum"];
            $data["totalScoreComputer"] = $_SESSION["computerScore"];
        }

        if ($_SESSION["computerScore"] === 21) {
            $data["message"] = "The computer got 21, it won!";
            return $this->showResult("computer", $data);
        }

        $data["message"] = "You won! Computer is over 21.";
        return $this->showResult("player", $data);
    }

    private function showResult(string $winner, array $data): array
    {
        if (isset($_SESSION['rounds'])) { // keep track of amount of rounds
            $_SESSION['rounds'] += 1;
        }

        // initialize necessary variables after first round
        if (!isset($_SESSION['rounds'])) {
            $_SESSION['rounds'] = 1;
            $_SESSION['playerWins'] = 0;
            $_SESSION['computerWins'] = 0;
        }

        // keep track of winners and movement of bitcoin
        if ($winner === "player") {
            $_SESSION['playerWins'] += 1;
            $_SESSION["playerBitcoin"] += $_SESSION["currentBet"];
            $_SESSION["computerBitcoin"] -= $_SESSION["currentBet"];
        } elseif ($winner === "computer") {
            $_SESSION['computerWins'] += 1;
            $_SESSION["playerBitcoin"] -= $_SESSION["currentBet"];
            $_SESSION["computerBitcoin"] += $_SESSION["currentBet"];
        }

        // reset round score
        $_SESSION["playerScore"] = 0;
        $_SESSION["computerScore"] = 0;

        // make sure $data has all necessary information for the view
        $data["title"] = "21";
        $data["rounds"] = $_SESSION['rounds'];
        $data["playerWins"] = $_SESSION['playerWins'];
        $data["computerWins"] = $_SESSION['computerWins'];
        $data["playerBitcoin"] = $_SESSION["playerBitcoin"];
        $data["computerBitcoin"] = $_SESSION["computerBitcoin"];
        $data["pageToRender"] = "layout/dice-winner.php";

        return $data;
    }

    private function resetGame(): array
    {
        destroySession();
        // start up bitcoins accounts by start of game
        if (!isset($_SESSION["playerBitcoin"])) {
            $_SESSION["playerBitcoin"] = 10;
            $_SESSION["computerBitcoin"] = 100;
        }
        return $this->welcome();
    }

    private function checkBet(int $bitcointBet): bool
    {
        return (bool) !(intval($bitcointBet) > (0.5 * $_SESSION["playerBitcoin"]));
    }

    private function startGame(int $firstRound = 1): array
    {
        // get how much the player wants to bet
        $bitcoinBet = isset($_POST['bitcoin']) ? intval(htmlentities($_POST['bitcoin'])) : null;

        // if not valid bet, return to welcome page again
        if (!$this->checkBet($bitcoinBet)) {
            if ($firstRound) {
                return $this->welcome();
            }

            $data = [
                "title" => "21",
                "header" => "Invalid bet!",
                "message" => "You can only bet maximum 50% of you bitcoins, please try again!",
                "rounds" => $_SESSION['rounds'],
                "playerWins" => $_SESSION['playerWins'],
                "computerWins" => $_SESSION['computerWins'],
                "playerBitcoin" => $_SESSION["playerBitcoin"],
                "computerBitcoin" => $_SESSION["computerBitcoin"],
                "pageToRender" => "layout/dice-winner.php"
            ];

            return $data;
        }

        // save the current bet
        $_SESSION["currentBet"] = $bitcoinBet;

        // check how many dice
        $dice = isset($_POST['dice']) ? htmlentities($_POST['dice']) : null;

        if ($dice) {
            // set the amount of dice for the game session
            $_SESSION["numDice"] = intval($dice);
        }

        // players start with score 0
        $_SESSION["playerScore"] = 0;
        $_SESSION["computerScore"] = 0;

        return $this->playerTurn();
    }

    public function playGame(): array
    {
        // start up bitcoins accounts by start of game
        if (!isset($_SESSION["playerBitcoin"])) {
            $_SESSION["playerBitcoin"] = 10;
            $_SESSION["computerBitcoin"] = 100;
        }

        // reset the game
        if (isset($_GET["reset"]) && $_GET["reset"] === "True") {
            return $this->resetGame();
        }

        // start first round
        if (isset($_POST['dice'])) {
            return $this->startGame();
        }

        // start next round
        if (isset($_POST["nextRound"])) {
            unset($_POST["nextRound"]);
            return $this->startGame(0);
        }

        // no game in progress
        if (!isset($_SESSION['playerScore'])) {
            return $this->welcome();
        }

        // computer's turn
        if (isset($_GET["turn"])) {
            return $this->computerTurn();
        }

        // game in progress
        return $this->playerTurn();
    }
}
