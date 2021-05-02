<?php

namespace App\Controller;

use App\DTO\Request\GetStatisticsRequest;
use App\DTO\Response\GetStatisticsResponse;
use App\Repository\HotelRepository;
use App\Repository\ReviewRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class StatisticsController extends BaseController
{
    private ReviewRepository $reviews;

    private HotelRepository $hotels;

    private DenormalizerInterface $denormalizer;

    public function __construct(HotelRepository $hotels, ReviewRepository $reviews, DenormalizerInterface $denormalizer)
    {
        $this->hotels = $hotels;
        $this->reviews = $reviews;
        $this->denormalizer = $denormalizer;
    }

    /**
     * @Route("/statistics", methods={"GET"}, name="statistics")
     */
    public function getStatistics(GetStatisticsRequest $request): Response
    {
        $data = $this->reviews->getStatistics($request);

        $statistics = $this->denormalizer->denormalize($data, GetStatisticsResponse::class.'[]', null, [
            ObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true
        ]);

        return $this->json($statistics);
    }
}
