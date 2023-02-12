<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MusicGroupRepository;
use App\Repository\EventRepository;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(MusicGroupRepository $musicGroupRepository): Response
    {
        $user = $this->getUser();
        $musicGroups = $musicGroupRepository->findBy(['manager' => $user->getId()]);

        return $this->render('front/profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'music_groups' => $musicGroups,
        ]);
    }
}
