<?php
namespace App\Middleware\Error;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Handler\NotFoundHandler as ZendNotFoundHandler;

class NotFoundHandler extends ZendNotFoundHandler
{
    /**
     * Creates and returns a 404 response.
     *
     * @param ServerRequestInterface $request
     * @return JsonResponse
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $responseData = [
            'success'   => false,
            'method'    => $request->getMethod(),
            'uri'       => (string) $request->getUri()
        ];

        return new JsonResponse($responseData, 404);
    }
}
