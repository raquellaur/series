<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use  Faker;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker  =  Faker\Factory::create('fr_FR');

        for( $i = 0; $i <= 5; $i++ ) {
            $randYear = rand(1990, 2005);
            for( $j = 1; $j <= 10; $j++ ) {
                $season = new Season();
                $season->setProgram($this->getReference('program_'. $i));
                $season->setNumber($j);
                $season->setYear($randYear++);
                $season->setDescription($faker->paragraph(5, true));
                $manager->persist($season);
                $this->addReference('season_'. $j . $i, $season);
             }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
