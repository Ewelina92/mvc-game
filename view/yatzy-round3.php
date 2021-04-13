<?php

/**
 * View template for the Yatzy game
 */

declare(strict_types=1);

$header = $header ?? null;
$message = $message ?? null;

?><h1><?= $header ?></h1>
<ul class="yatzy-ul">
    <li>
    <p>Current result, empty slots are available</p>
        <ul>
        <li>Ones: <?= $slot1 ?></li>
        <li>Twos: <?= $slot2 ?></li>
        <li>Threes: <?= $slot3 ?></li>
        <li>Fours: <?= $slot4 ?></li>
        <li>Fives: <?= $slot5 ?></li>
        <li>Sixes: <?= $slot6 ?></li>
        </ul>
    </li>
</ul>
<p><?= $message ?> <?= $diceHandRoll ?></p>

<form method="post" action="yatzy">
    <p><input type="submit" name="checkTurnResult" value="Continue"></p>
</form>