<?php

declare(strict_types=1);

namespace Eaja20\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;

use function Eaja20\Functions\renderView;

/**
 * Base controller trait with utilities for controllers.
 */
trait ControllerTrait
{
    protected function createResponse(
        string $body,
        int $status = 200
    ): ResponseInterface {
        $psr17Factory = new Psr17Factory();

        return $psr17Factory
            ->createResponse($status)
            ->withBody($psr17Factory->createStream($body));
    }

    protected function redirect(
        string $url,
        int $status = 301
    ): ResponseInterface {
        $psr17Factory = new Psr17Factory();

        return $psr17Factory
            ->createResponse($status)
            ->withHeader("Location", $url);
    }
}
