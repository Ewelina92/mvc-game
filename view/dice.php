<?php

/**
 * Standard view template to generate a simple web page, or part of a web page.
 */

declare(strict_types=1);

// use Eaja20\Dice\Dice;
// use Eaja20\Dice\DiceHand;

$header = $header ?? null;
$message = $message ?? null;

// $die = new Dice();
// $die->roll();

// $diceHand = new DiceHand();
// $diceHand->roll();

?><h1><?= $header ?></h1>

<p><?= $message ?></p>

<p><?= $diceHandRoll ?> The sum of this throw is: <?= $roundSum ?></p>
<p>Your total score is: <?= $totalScorePlayer ?></p>

<button onClick="window.location.href='dice';">Roll again</button>
<!-- <button onClick="noop();">Don't roll again</button> -->
<a href="?turn=computer">Stop rolling and let the computer roll</a>

<!-- <a href="?reset=True">Reset game</a> -->