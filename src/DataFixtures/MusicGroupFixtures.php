<?php

namespace App\DataFixtures;

use App\Entity\MusicGroup;
use App\Entity\NewsGroup;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MusicGroupFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $users = $manager->getRepository(User::class)->findAll();

        foreach ($users as $user) {
            if($user->getRoles()[0] == 'ROLE_MANAGER'){
                $musicGroup = new MusicGroup();
                $musicGroup->setName('The Beatles'.$user->getId());
                $musicGroup->setManager($user);
                $musicGroup->addArtiste($user);
                $manager->persist($musicGroup);
            }
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
