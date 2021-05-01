<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ErrorController extends AbstractController
{
    /**
     * @var bool
     */
    private $debug;

    public function __construct(bool $debug = false)
    {
        $this->debug = $debug;
    }

    public function show(FlattenException $exception): JsonResponse
    {
        $error = $this->getError($exception);

        $response = new JsonResponse(
            $error,
            $error['code']
        );

        $response->headers->set('Content-Type', 'application/json; charset=UTF-8');

        return $response;
    }

    protected function getError(FlattenException $exception): array
    {
        $error = [
            'code' => 400,
            'message' => $exception->getMessage(),
        ];

        if ($this->debug) {
            $error['debug'] = sprintf(
                'Exception [%s] (%s) in file %s, line %d',
                $exception->getClass(),
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            );
        }

        return $error;
    }
}
