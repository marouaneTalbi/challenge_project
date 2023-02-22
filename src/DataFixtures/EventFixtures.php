<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\MusicGroup;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $musicGroups = $manager->getRepository(MusicGroup::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();
        $faker = Factory::create('fr_FR');

        for($i =0; $i< 10; $i++){
            $event = new Event();
            $event->setName('event Nameddd'.$i)
                ->setType('event Type'.$i)
                ->setLieu('event Lieu'.$i)
                ->setHeure(new \DateTime(date('Y-m-d H:i:s')))
                ->setDate(new \DateTime(date('H:i:s')))
                ->setDescription('description'.$i)
                ->setMusicGroup($faker->randomElement($musicGroups))
                ->addInvite($faker->randomElement($users))
                ->setPublic(true)
            ;
            $manager->persist($event);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ];
    }
}