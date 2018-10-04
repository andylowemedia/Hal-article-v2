<?php

namespace App\Middleware\Error;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

use Zend\Diactoros\Response\JsonResponse;

class JsonErrorResponseGenerator
{
    /**
     * @var bool
     */
    private $debug = false;

    /**
     * @var string
     */
    private $stackTraceTemplate = <<<'EOT'
%s raised in file %s line %d:
Message: %s
Stack Trace:
%s

EOT;

    /**
     * @param bool $isDevelopmentMode
     * @param null|TemplateRendererInterface $renderer
     * @param string $template
     */
    public function __construct(
        $isDevelopmentMode = false
    ) {
        $this->debug     = (bool) $isDevelopmentMode;
    }

    /**
     * @param \Throwable|\Exception $e
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke($e, ServerRequestInterface $request, ResponseInterface $response)
    {
        $code = $e->getCode() === 0 ? 500 : $e->getCode();

        return new JsonResponse(
            $this->prepareResponseData($e),
            $code,
            $response->getHeaders()
        );
    }

    private function prepareResponseData($e)
    {
        $responseData = [
            'success' => false,
            'message' => $e->getMessage()
        ];

        if ($this->debug) {
            $responseData['exception']  = get_class($e);
            $responseData['fileName']   = $e->getFile();
            $responseData['line']       = $e->getLine();
            $responseData['message']    = $e->getMessage();
            $responseData['stackTrace'] = $e->getTraceAsString();
        }

        return $responseData;
    }
}
