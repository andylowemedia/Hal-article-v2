<?php
namespace App\Middleware\Error;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Delegate\NotFoundDelegate as ZendNotFoundDelegate;

class NotFoundDelegate extends ZendNotFoundDelegate
{
    /**
     * Creates and returns a 404 response.
     *
     * @param ServerRequestInterface $request
     * @return JsonResponse
     */
    public function process(ServerRequestInterface $request)
    {
        $responseData = [
            'success'   => false,
            'method'    => $request->getMethod(),
            'uri'       => (string) $request->getUri()
        ];

        return new JsonResponse($responseData, 404);
        ;
    }
}
