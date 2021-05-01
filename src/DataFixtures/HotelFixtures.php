<?php

namespace App\DataFixtures;

use App\Factory\HotelFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HotelFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        HotelFactory::createMany(10);

        $manager->flush();
    }
}
