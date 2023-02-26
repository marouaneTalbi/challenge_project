<?php

namespace App\DataFixtures;

use App\Entity\MusicGroup;
use App\Entity\NewsGroup;
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
            $news = new NewsGroup();
            $news->setPost('The Beatles'.$i);
            $news->setAuthor($faker->randomElement($users));
            $news->setGroupe($faker->randomElement($musicGroups));
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