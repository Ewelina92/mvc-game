<?php

declare(strict_types=1);

namespace Eaja20\Controller;

//use Nyholm\Psr7\Factory\Psr17Factory;
//use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

use function Eaja20\Functions\{
    destroySession,
    renderView,
    url
};

/**
 * Controller for the session routes.
 */
class Session //extends ControllerBase
{
    use ControllerTrait;

    public function index(): ResponseInterface
    {
        $body = renderView("layout/session.php");
        return $this->createResponse($body);
    }



    public function destroy(): ResponseInterface
    {
        destroySession();
        return $this->redirect(url("/session"));
        // return (new Response())
        //     ->withStatus(301)
        //     ->withHeader("Location", url("/session"));
    }
}
