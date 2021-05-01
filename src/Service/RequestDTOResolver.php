<?php


namespace App\Service;


use App\DTO\Request\RequestDTOInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestDTOResolver implements ArgumentValueResolverInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function supports(Request $request, ArgumentMetadata $argument)
    {
        $reflection = new \ReflectionClass($argument->getType());
        if ($reflection->implementsInterface(RequestDTOInterface::class)) {
            return true;
        }

        return false;
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $class = $argument->getType();

        $dto = new $class($request);

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            throw new BadRequestHttpException(
                implode(', ', array_map(function (ConstraintViolationInterface $error) {
                    return $error->getMessage();
                }, iterator_to_array($errors))
            ));
        }

        yield $dto;
    }
}