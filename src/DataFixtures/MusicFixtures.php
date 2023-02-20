<?php

namespace App\DataFixtures;

use App\Entity\Music;
use App\Entity\MusicGroup;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class MusicFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $musicGroups = $manager->getRepository(MusicGroup::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();
        $faker = Factory::create('fr_FR');

        for($i =0; $i< 10; $i++){
            $music = new Music();
            $music
                ->setUrl('url'.$i)
                ->setName('music'.$i)
                ->setType('type'.$i)
                ->setSize('size'.$i)
                ->setOwnerMusicGroup($faker->randomElement($musicGroups))
                ->setUserOwner($faker->randomElement($users))
                ;
            $manager->persist($music);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            MusicGroupFixtures::class,
        ];
    }
}
