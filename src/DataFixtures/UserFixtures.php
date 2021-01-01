<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $contributor = new User();
        $contributor->setEmail('contributor1@monsite.com');
        $contributor->setRoles(['ROLE_CONTRIBUTOR']);
        $contributor->setPassword($this->passwordEncoder->encodePassword(
            $contributor,
            'contributor1password'
        ));
        $manager->persist($contributor);
        $this->addReference('contributor1', $contributor);

        $contributor = new User();
        $contributor->setEmail('contributor2@monsite.com');
        $contributor->setRoles(['ROLE_CONTRIBUTOR']);
        $contributor->setPassword($this->passwordEncoder->encodePassword(
            $contributor,
            'contributor2password'
        ));
        $manager->persist($contributor);
        $this->addReference('contributor2', $contributor);


        $admin = new User();
        $admin->setEmail('admin@monsite.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'adminpassword'
        ));
        $manager->persist($admin);
        $this->addReference('admin', $admin);

        $manager->flush();
    }
}
