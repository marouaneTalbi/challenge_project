<?php

namespace App\Controller\Front;

use App\Repository\UserRepository;
use App\Repository\MusicGroupRepository;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_FAN')]
class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_index')]
    public function index(): Response
    {
        return $this->render('front/default/index.html.twig');
    }

    #[Route('/artistes', name: 'default_artists')]
    public function getAllArtists(UserRepository $userRepository): Response
    {
        $artists = $userRepository->findByRole('ROLE_ARTIST');
        return $this->render('front/default/artist/index.html.twig', [
            'artists' => $artists
        ]);
    }

    #[Route('/music_groups', name: 'default_music_groups')]
    public function getAllMusicGroups(MusicGroupRepository $musicGroupRepository): Response
    {
        $music_groups = $musicGroupRepository->findAll();
        return $this->render('front/music_group/index.html.twig', [
            'music_groups' => $music_groups
        ]);
    }
}
