<?php

namespace App\Controller;

use App\DTO\Request\GetStatisticsRequest;
use App\DTO\Response\Transformer\StatisticsResponseDTOTransformer;
use App\Entity\Hotel;
use App\Repository\HotelRepository;
use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StatisticsController extends BaseController
{
    private ReviewRepository $reviews;

    private HotelRepository $hotels;

    public function __construct(HotelRepository $hotels, ReviewRepository $reviews)
    {
        $this->hotels = $hotels;
        $this->reviews = $reviews;
    }

    /**
     * @Route("/statistics", methods={"GET"}, name="statistics")
     */
    public function index(GetStatisticsRequest $request): Response
    {
        $data = $this->reviews->getStatistics($request);

        return $this->json($data);
    }
}
