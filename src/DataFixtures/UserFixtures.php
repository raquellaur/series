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
        $contributor1 = new User();
        $contributor1->setEmail('contributor1@monsite.com');
        $contributor1->setRoles(['ROLE_CONTRIBUTOR']);
        $contributor1->setPassword($this->passwordEncoder->encodePassword(
            $contributor1,
            'contributor1password'
        ));
        $manager->persist($contributor1);
        $this->addReference('contributor1', $contributor1);

        $contributor2 = new User();
        $contributor2->setEmail('contributor2@monsite.com');
        $contributor2->setRoles(['ROLE_CONTRIBUTOR']);
        $contributor2->setPassword($this->passwordEncoder->encodePassword(
            $contributor2,
            'contributor2password'
        ));
        $manager->persist($contributor2);
        $this->addReference('contributor2', $contributor2);


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
