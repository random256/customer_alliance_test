<?php


namespace App\UseCase\Request;

use Symfony\Component\Validator\Constraints as Assert;

class GetStatisticsCommand implements RequestCommandInterface
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
}