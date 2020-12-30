<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const ACTOR = [
        'Greg Nicotero',
        'Michael E. Satrazemis',
        'Ernest R. Dickerson',
        'David Boyd',
    ];
    public function load(ObjectManager $manager)
    {
        foreach (self::ACTOR as $key => $data)
        {
          $actor = new Actor();
          $actor->setName($data);
          $actor->addProgram($this->getReference('walking'));
          $manager->persist($actor);

        }
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}