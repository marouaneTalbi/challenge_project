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

            $music = new Music();
            $music
                ->setUrl('central_cee.mp3')
                ->setName('Cenrtral cee')
                ->setType('mp3')
                ->setSize('64Ko')
                ->setOwnerMusicGroup($faker->randomElement($musicGroups))
                ->setUserOwner($faker->randomElement($users))
                ;
            $manager->persist($music);

            $music = new Music();
            $music
                ->setUrl('kaneki_mmz.mp3')
                ->setName('MMZ KANEKI')
                ->setType('mp3')
                ->setSize('64Ko')
                ->setOwnerMusicGroup($faker->randomElement($musicGroups))
                ->setUserOwner($faker->randomElement($users))
            ;
            $manager->persist($music);

        $music = new Music();
        $music
            ->setUrl('kekra_canne_a_peche.mp3')
            ->setName('Kekra Canne à pêche')
            ->setType('mp3')
            ->setSize('64Ko')
            ->setOwnerMusicGroup($faker->randomElement($musicGroups))
            ->setUserOwner($faker->randomElement($users))
        ;
        $manager->persist($music);

        $music = new Music();
        $music
            ->setUrl('kekra_iverson.mp3')
            ->setName('Kekra Iverson')
            ->setType('mp3')
            ->setSize('64Ko')
            ->setOwnerMusicGroup($faker->randomElement($musicGroups))
            ->setUserOwner($faker->randomElement($users))
        ;
        $manager->persist($music);

        $music = new Music();
        $music
            ->setUrl('mira_pnl.mp3')
            ->setName('Pnl Mira')
            ->setType('mp3')
            ->setSize('64Ko')
            ->setOwnerMusicGroup($faker->randomElement($musicGroups))
            ->setUserOwner($faker->randomElement($users))
        ;
        $manager->persist($music);


        $music = new Music();
        $music
            ->setUrl('mmz_cauchemar.mp3')
            ->setName('MMZ Cauchemar')
            ->setType('mp3')
            ->setSize('64Ko')
            ->setOwnerMusicGroup($faker->randomElement($musicGroups))
            ->setUserOwner($faker->randomElement($users))
        ;
        $manager->persist($music);


        $music = new Music();
        $music
            ->setUrl('nahir_freeze.mp3')
            ->setName('Nahir Freeze')
            ->setType('mp3')
            ->setSize('64Ko')
            ->setOwnerMusicGroup($faker->randomElement($musicGroups))
            ->setUserOwner($faker->randomElement($users))
        ;
        $manager->persist($music);

        $music = new Music();
        $music
            ->setUrl('niro_pret.mp3')
            ->setName('Niro pret')
            ->setType('mp3')
            ->setSize('64Ko')
            ->setOwnerMusicGroup($faker->randomElement($musicGroups))
            ->setUserOwner($faker->randomElement($users))
        ;
        $manager->persist($music);

        $music = new Music();
        $music
            ->setUrl('rimk_ninho.mp3')
            ->setName('RimK Ninho')
            ->setType('mp3')
            ->setSize('64Ko')
            ->setOwnerMusicGroup($faker->randomElement($musicGroups))
            ->setUserOwner($faker->randomElement($users))
        ;
        $manager->persist($music);

        $music = new Music();
        $music
            ->setUrl('ziak.mp3')
            ->setName('Ziak')
            ->setType('mp3')
            ->setSize('64Ko')
            ->setOwnerMusicGroup($faker->randomElement($musicGroups))
            ->setUserOwner($faker->randomElement($users))
        ;
        $manager->persist($music);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            MusicGroupFixtures::class,
        ];
    }
}
