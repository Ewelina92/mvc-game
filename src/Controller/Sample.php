<?php

declare(strict_types=1);

namespace Eaja20\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;

use function Eaja20\Functions\renderView;

/**
 * Controller for a sample route an controller class.
 */
class Sample
{
    public function where(): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        $data = [
            "header" => "Rainbow page",
            "message" => "Hey, edit this to do it youreself!",
        ];

        $body = renderView("layout/page.php", $data);

        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }
}
