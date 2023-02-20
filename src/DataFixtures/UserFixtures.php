<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{

    public const USER_REFERENCE = 'ROLE_MANAGER';

    public function load(ObjectManager $manager): void
    {
        $events = $manager->getRepository(Event::class)->findAll();
        $faker = Factory::create('fr_FR');

        // pwd = test
        $pwd = '$2y$13$r/sNDkWI9w4h0XHSIYqYJusHu3JYZTFwEOxTCkXG31rL9Dy1Tncba';
       
        $object = (new User())
            ->setEmail('fan@user.fr')
            ->setFirstName('fan Firstname')
            ->setRoles(['ROLE_FAN'])
            ->setIsEnabled(true)
            ->setIsDeleted(false)
            ->setPassword($pwd)
        ;
        $manager->persist($object);

        for($i =0; $i< 10; $i++){
            $object = (new User())
                ->setEmail('manager'.$i.'@user.fr')
                ->setFirstName('manager Firstname'. $i)
                ->setRoles(['ROLE_MANAGER'])
                ->setIsEnabled(true)
                ->setIsDeleted(false)
                ->setPassword($pwd)
                //->addEvent($faker->randomElement($events))

            ;
            $manager->persist($object);
        }

        for($i =0; $i< 10; $i++){
            $object = (new User())
                ->setEmail('artist'.$i.'@user.fr')
                ->setFirstName('Artist Firstname'. $i)
                ->setRoles(['ROLE_ARTIST'])
                ->setIsEnabled(true)
                ->setIsDeleted(false)
                ->setPassword($pwd)
                ;
        $manager->persist($object);
        }

        $object = (new User())
            ->setEmail('admin@user.fr')
            ->setFirstName('Admin Firstname')
            ->setRoles(['ROLE_ADMIN'])
            ->setIsEnabled(true)
            ->setIsDeleted(false)
            ->setPassword($pwd)
           // ->addEvent($faker->randomElement($events))

        ;

        $manager->persist($object);
        $manager->flush();

    }

}
