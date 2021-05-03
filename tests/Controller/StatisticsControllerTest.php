<?php

namespace App\Tests\Controller;

use App\DTO\Request\GetStatisticsRequest;
use App\Factory\HotelFactory;
use App\Tests\TransactionTestCase;
use Carbon\Carbon;
use GuzzleHttp\RequestOptions;

class StatisticsControllerTest extends TransactionTestCase
{
    public function testGetStatistics_OK_group_daily()
    {
        $hotel = $this->createHotel();

        $today = Carbon::today();
        $weekAgo = $today->clone()->subWeek();

        $this->createReview($hotel, $today->clone()->subDays(2), 2);
        $this->createReview($hotel, $today->clone()->subDays(1), 1);

        $this->client->request('GET', '/statistics', [
             'hotel_id' => $hotel->getId(),
             'date_from' => $weekAgo->format(GetStatisticsRequest::DATETIME_FORMAT),
             'date_to' => $today->format(GetStatisticsRequest::DATETIME_FORMAT),
        ]);

        $this->assertResponseIsSuccessful();
        $statistics = $this->decode($this->client->getResponse());

        $this->assertCount(2, $statistics);

        foreach ($statistics as $statistic) {
            $this->assertArrayHasKey('review_count', $statistic);
            $this->assertArrayHasKey('average_score', $statistic);
            $this->assertArrayHasKey('date_group', $statistic);
        }

        $this->assertEquals(1, $statistics[0]['review_count']);
        $this->assertEquals(2, $statistics[0]['average_score']);
        $this->assertEquals($today->clone()->subDays(2)->format('Y-m-d'), $statistics[0]['date_group']);

        $this->assertEquals(1, $statistics[1]['review_count']);
        $this->assertEquals(1, $statistics[1]['average_score']);
        $this->assertEquals($today->clone()->subDays(1)->format('Y-m-d'), $statistics[1]['date_group']);
    }

    public function testGetStatistics_OK_group_weekly()
    {
        $hotel = $this->createHotel();

        $today = Carbon::today();
        $monthAgo = $today->clone()->subMonth();

        $this->createReview($hotel, $today->clone()->subDays(2), 2);
        $this->createReview($hotel, $today->clone()->subDays(1), 1);

        $this->client->request('GET', '/statistics', [
             'hotel_id' => $hotel->getId(),
             'date_from' => $monthAgo->format(GetStatisticsRequest::DATETIME_FORMAT),
             'date_to' => $today->format(GetStatisticsRequest::DATETIME_FORMAT),
        ]);

        $this->assertResponseIsSuccessful();
        $statistics = $this->decode($this->client->getResponse());

        $this->assertCount(1, $statistics);

        foreach ($statistics as $statistic) {
            $this->assertArrayHasKey('review_count', $statistic);
            $this->assertArrayHasKey('average_score', $statistic);
            $this->assertArrayHasKey('date_group', $statistic);
        }

        $this->assertEquals(2, $statistics[0]['review_count']);
        $this->assertEquals(1.5, $statistics[0]['average_score']);
        $this->assertEquals($today->clone()->subDays(2)->format('Y/W'), $statistics[0]['date_group']);
    }

    public function testGetStatistics_OK_group_monthly()
    {
        $hotel = $this->createHotel();

        $today = Carbon::today();
        $month4Ago = $today->clone()->subMonths(4);

        $this->createReview($hotel, $today->clone()->subDays(2), 2);
        $this->createReview($hotel, $today->clone()->subDays(1), 1);

        $this->client->request('GET', '/statistics', [
             'hotel_id' => $hotel->getId(),
             'date_from' => $month4Ago->format(GetStatisticsRequest::DATETIME_FORMAT),
             'date_to' => $today->format(GetStatisticsRequest::DATETIME_FORMAT),
        ]);

        $this->assertResponseIsSuccessful();
        $statistics = $this->decode($this->client->getResponse());

        $this->assertCount(1, $statistics);

        foreach ($statistics as $statistic) {
            $this->assertArrayHasKey('review_count', $statistic);
            $this->assertArrayHasKey('average_score', $statistic);
            $this->assertArrayHasKey('date_group', $statistic);
        }

        $this->assertEquals(2, $statistics[0]['review_count']);
        $this->assertEquals(1.5, $statistics[0]['average_score']);
        $this->assertEquals($today->clone()->subDays(2)->format('Y-m'), $statistics[0]['date_group']);
    }

    public function testGetStatistics_OK_hotel_no_reviews()
    {
        $hotel = $this->createHotel();
        $hotelNoReviews = $this->createHotel();

        $today = Carbon::today();
        $month4Ago = $today->clone()->subMonths(4);

        $this->createReview($hotel, $today->clone()->subDays(2), 2);
        $this->createReview($hotel, $today->clone()->subDays(1), 1);

        $this->client->request('GET', '/statistics', [
             'hotel_id' => $hotelNoReviews->getId(),
             'date_from' => $month4Ago->format(GetStatisticsRequest::DATETIME_FORMAT),
             'date_to' => $today->format(GetStatisticsRequest::DATETIME_FORMAT),
        ]);

        $this->assertResponseIsSuccessful();
        $statistics = $this->decode($this->client->getResponse());

        $this->assertCount(0, $statistics);
    }

    public function testGetStatistics_404_hotel_not_found()
    {
        $today = Carbon::today();
        $month4Ago = $today->clone()->subMonths(4);

        $this->client->request('GET', '/statistics', [
             'hotel_id' => '123',
             'date_from' => $month4Ago->format(GetStatisticsRequest::DATETIME_FORMAT),
             'date_to' => $today->format(GetStatisticsRequest::DATETIME_FORMAT),
        ]);

        $this->assertResponseStatusCodeSame(404);
        $error = $this->decode($this->client->getResponse());

        $this->assertArrayHasKey('code', $error);
        $this->assertArrayHasKey('message', $error);

        $this->assertEquals(404, $error['code']);
        $this->assertEquals('Hotel [123] not found', $error['message']);
    }

    public function testGetStatistics_400_hotel_id_required_date_from_required_date_to_required()
    {
        $this->client->request('GET', '/statistics', [
        ]);

        $this->assertResponseStatusCodeSame(400);
        $error = $this->decode($this->client->getResponse());

        $this->assertArrayHasKey('code', $error);
        $this->assertArrayHasKey('message', $error);

        $this->assertEquals(400, $error['code']);
        $this->assertEquals('hotel_id is required, date_from is required, date_to is required', $error['message']);
    }

    public function testGetStatistics_400_date_from_date_to_must_be_valid_date()
    {
        $this->client->request('GET', '/statistics', [
            'hotel_id' => 'sdf',
            'date_from' => 'sdf',
            'date_to' => 'sdf',
        ]);

        $this->assertResponseStatusCodeSame(400);
        $error = $this->decode($this->client->getResponse());

        $this->assertArrayHasKey('code', $error);
        $this->assertArrayHasKey('message', $error);

        $this->assertEquals(400, $error['code']);
        $this->assertEquals('date_to must be a valid datetime, date_to must be a valid datetime', $error['message']);
    }

    public function testGetStatistics_400_date_from_must_be_less_than_date_to()
    {
        $this->client->request('GET', '/statistics', [
            'hotel_id' => 'sdf',
            'date_from' => Carbon::now()->format(GetStatisticsRequest::DATETIME_FORMAT),
            'date_to' => Carbon::now()->subDay()->format(GetStatisticsRequest::DATETIME_FORMAT),
        ]);

        $this->assertResponseStatusCodeSame(400);
        $error = $this->decode($this->client->getResponse());

        $this->assertArrayHasKey('code', $error);
        $this->assertArrayHasKey('message', $error);

        $this->assertEquals(400, $error['code']);
        $this->assertEquals('date_from must be less than date_to', $error['message']);
    }
}
