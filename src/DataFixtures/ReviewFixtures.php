<?php

namespace App\DataFixtures;

use App\Factory\HotelFactory;
use App\Factory\ReviewFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ReviewFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        ReviewFactory::createMany(
            1000,
            function() {
                return [
                    'hotel' => HotelFactory::random()
                ];
            }
        );

        $manager->flush();
    }
}
