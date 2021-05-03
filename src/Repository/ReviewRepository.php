<?php

namespace App\Repository;

use App\DTO\Request\GetStatisticsRequest;
use App\Entity\Review;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    private HotelRepository $hotels;

    public function __construct(ManagerRegistry $registry, HotelRepository $hotelRepository)
    {
        parent::__construct($registry, Review::class);

        $this->hotels = $hotelRepository;
    }

    /**
     * @param GetStatisticsRequest $request
     * @return array
     */
    public function getStatistics(GetStatisticsRequest $request): array
    {
        $hotel = $this->hotels->findByID($request->hotel_id);

        $dateFrom = Carbon::parse($request->date_from);
        $dateTo = Carbon::parse($request->date_to);

        $daysDiff = $dateTo->diffInDays($dateFrom);

        $qb = $this->createQueryBuilder('r')
            ->select('count(r) as review_count')
            ->addSelect('avg(r.score) as average_score')
            ->addSelect('DATE_FORMAT(r.created_date, :date_format) as date_group')
            ->andWhere('r.created_date BETWEEN :date_from and :date_to')
            ->andWhere('r.hotel = :hotel')
            ->setParameters([
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'hotel' => $hotel,
                'date_format' => $this->getMYSQLDateFormat($daysDiff),
            ])
            ->groupBy('date_group')
            ->orderBy('date_group')
        ;

        return $qb->getQuery()->getResult();
    }

    protected function getMYSQLDateFormat(int $daysDiff): string
    {
        if ($daysDiff < 30) {
            // - 1 - 29 days: Grouped daily
            return '%Y-%m-%d';
        }
        if ($daysDiff < 90) {
            // - 30 - 89 days: Grouped weekly
            return '%Y/%u';
        }

        // - More than 89 days: Grouped monthly
        return '%Y-%m';
    }
}
