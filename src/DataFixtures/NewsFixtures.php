<?php

namespace App\DataFixtures;

use App\Entity\MusicGroup;
use App\Entity\News;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class NewsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $users = $manager->getRepository(User::class)->findAll();
        $musicGroups = $manager->getRepository(MusicGroup::class)->findAll();
        $faker = Factory::create('fr_FR');

        for($i = 0; $i < 10; $i++){
            $news = new News();
            $news->setPost('The Beatles'.$i);
            $news->setStatus('Status'.$i);
            $news->setGroupId($faker->randomElement($musicGroups));
            $news->setUserId($faker->randomElement($users));
            $manager->persist($news);
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