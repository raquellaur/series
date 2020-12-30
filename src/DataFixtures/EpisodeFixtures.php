<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use App\Service\Slugify;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    private $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager)
    {
        $faker  =  Faker\Factory::create('fr_FR');

        for( $i = 0; $i <= 5; $i++ ) {
            for( $j = 1; $j <= 10; $j++ ) {
                for ( $x = 1; $x <= 10; $x++){
                    $episode = new Episode();
                    $episode->setNumber($x);
                    $episode->setTitle($faker->text($maxNbChars = 100));
                    $episode->setSlug($this->slugify->generate($episode->getTitle()));
                    $episode->setSynopsis($faker->paragraph(10, true));
                    $episode->setSeason($this->getReference('season_'. $j . $i));
                    $manager->persist($episode);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}
