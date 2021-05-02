<?php


namespace App\DTO\Response;


class GetStatisticsResponse
{
    public int $review_count;
    public float $average_score;
    public string $date_group;
}