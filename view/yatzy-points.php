<?php

/**
 * Standard view template to generate a simple web page, or part of a web page.
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
<p class="bold"><?= $message ?></p>

<form id="saveDice" method="post" action="yatzy">

    <?php foreach ($options as $key => $value) { ?>
        <p><input type="radio" required name="choice" value="<?= $key ?>:<?= $value ?>">Put <?= $value ?> points into slot <?= $key ?></p>
    <?php } ?>
        
    <p><input type="submit" name="assignPoints" value="continue"></p>
</form>
