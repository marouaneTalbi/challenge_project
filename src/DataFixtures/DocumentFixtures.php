<?php

namespace App\DataFixtures;

use App\Entity\Document;
use App\Entity\MusicGroup;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class DocumentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $musicGroups = $manager->getRepository(MusicGroup::class)->findAll();
        $faker = Factory::create('fr_FR');

        for($i =0; $i< 10; $i++){
          $document = new Document();
          $document
              ->setName('document Nameddd'.$i)
              ->setType('type'.$i)
              ->setUrl('url'.$i)
              ->setMusicGroup($faker->randomElement($musicGroups))
              ->setSize('size'.$i)
            ;
            $manager->persist($document);
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
