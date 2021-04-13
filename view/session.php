<?php

/**
 * Standard view template to generate a simple web page, or part of a web page.
 */

declare(strict_types=1);

use function Eaja20\Functions\url;

$url = url("/session/destroy");

echo <<<EOD
<h1>Session details</h1>
<p>Here are some details on the session. Reload this page to see the counter increment itself.</p>
<p>You may <a href="$url">destroy the session</a> if you like, good for dealing
with trouble.</p>
EOD;

var_dump(session_name());
var_dump($_SESSION);

echo <<<EOD
<h1>POST details</h1>
EOD;
var_dump($_POST);

$_SESSION["counter"] = 1 + ($_SESSION["counter"] ?? 0);
