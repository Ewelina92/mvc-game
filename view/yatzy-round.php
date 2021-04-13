<?php

/**
 * Standard view template to generate a simple web page, or part of a web page.
 */

declare(strict_types=1);

$header = $header ?? null;
$message = $message ?? null;
$diceHandRoll = $diceHandRoll ?? null;

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

<?php if ($turn < 3) : ?>
<form method="post" action="yatzy">
    <p class="bold">Choose if you want to save any dice for the next roll</p>

    <?php for ($i = 0; $i < $amountOfDice; $i++) { ?>
        <p><input type="checkbox" name="ydice<?= $i + 1 ?>" value="<?= ${"diceValue" . $i} ?>">Dice with value: <?= ${"diceValue" . $i} ?></p>
    <?php } ?>
        <p><input type="submit" name="rollAgain" value="Continue"></p>
</form>

<?php else : ?>
<form method="post" action="yatzy">
    <p><input type="submit" name="checkTurnResult" value="Continue"></p>
</form>

<?php endif; ?>
