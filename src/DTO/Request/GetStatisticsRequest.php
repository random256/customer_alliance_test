<?php


namespace App\DTO\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class GetStatisticsRequest implements RequestDTOInterface
{
    /**
     * @var string
     * @var @Assert\NotBlank(message="hotel_id is required")
     */
    public $hotel_id;

    /**
     * @var @Assert\NotBlank(message="date_from is required")
     * @var @Assert\DateTime(message="date_to must be a valid datetime")
     * @var string A "Y-m-d H:i:s" formatted value
     */
    public $date_from;

    /**
     * @var @Assert\NotBlank(message="date_to is required")
     * @var @Assert\DateTime(message="date_to must be a valid datetime")
     * @var string A "Y-m-d H:i:s" formatted value
     */
    public $date_to;

    public function __construct(Request $request)
    {
        $this->hotel_id = $request->query->get('hotel_id');
        $this->date_from = $request->query->get('date_from');
        $this->date_to = $request->query->get('date_to');
    }
    
    /**
     * @Assert\Callback()
     * @param ExecutionContextInterface $context
     * @param $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->date_from > $this->date_to ) {
            $context->buildViolation('date_from must be less than date_to')
                ->atPath('date_from')
                ->addViolation();
        }
    }
}