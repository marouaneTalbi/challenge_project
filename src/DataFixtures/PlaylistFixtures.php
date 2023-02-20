<?php

namespace App\DataFixtures;

use App\Entity\Music;
use App\Entity\MusicGroup;
use App\Entity\Playlist;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class PlaylistFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $musicGroups = $manager->getRepository(MusicGroup::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();
        $music = $manager->getRepository(Music::class)->findAll();
        $faker = Factory::create('fr_FR');

        for($i =0; $i< 10; $i++){
            $playlist = new Playlist();
            $playlist
                ->setName('playlist'.$i)
                ->setImage('image'.$i)
                ->addMusic($faker->randomElement($music))
                ->setPublic(false)
                ->setOwner($faker->randomElement($users))
            ;
            $manager->persist($playlist);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            MusicFixtures::class,
            UserFixtures::class
        ];
    }
}
