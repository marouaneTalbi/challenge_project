<?php

namespace App\DataFixtures;

use App\Entity\MusicGroup;
use App\Entity\Subscription;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class SubscriptionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $users = $manager->getRepository(User::class)->findAll();
        $musicGroups = $manager->getRepository(MusicGroup::class)->findAll();
        $faker = Factory::create('fr_FR');

        for($i = 0; $i < 10; $i++){
            $subscription = new Subscription();
            $subscription->setGroupId($faker->randomElement($musicGroups));
            $subscription->setUserId($faker->randomElement($users));
            $manager->persist($subscription);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            MusicGroupFixtures::class,
        ];
    }

}