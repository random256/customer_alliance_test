<?php

namespace App\Controller;

use App\DTO\Request\RequestDTOInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var ValidatorInterface
     */
    protected $validator;
    /**
     * @var DenormalizerInterface
     */
    protected $denormalizer;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        DenormalizerInterface $denormalizer
    )
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->denormalizer = $denormalizer;
    }

    protected function validate(RequestDTOInterface $requestCommand)
    {
        $violations = $this->validator->validate($requestCommand);
        if (count($violations)) {
            throw new BadRequestHttpException(implode(', ',
                array_map(function (ConstraintViolationInterface $violation) {
                    return $violation->getMessage();
                }, iterator_to_array($violations))));
        }
    }
}
