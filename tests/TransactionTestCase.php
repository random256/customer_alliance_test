<?php

namespace App\Tests;

use App\Entity\Hotel;
use App\Entity\Review;
use App\Factory\HotelFactory;
use App\Factory\ReviewFactory;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TransactionTestCase extends WebTestCase
{
    protected EntityManagerInterface $em;
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->disableReboot();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->em->beginTransaction();
    }

    protected function tearDown(): void
    {
        $this->em->rollback();
        $this->em->close();

        parent::tearDown();
    }

    protected function createHotel(): Hotel
    {
        return HotelFactory::createOne()->object();
    }

    protected function createReview(Hotel $hotel, \DateTime $createdDate = null, int $score = null): Review
    {
        return ReviewFactory::createOne([
            'hotel' => $hotel,
            'created_date' => $createdDate ?? Carbon::now(),
            'score' => $score ?? random_int(1, 10),
        ])->object();
    }

    protected function decode(Response $response)
    {
        return json_decode($response->getContent(), true);
    }
}
