<?php

namespace App\Controller\Front;

use App\Repository\UserRepository;
use App\Repository\MusicGroupRepository;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


#[IsGranted('ROLE_FAN')]
class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_index')]
    public function index(): Response
    {
        return $this->render('front/default/index.html.twig');
    }

    #[Route('/artists', name: 'default_artists')]
    public function getAllArtists(UserRepository $userRepository): Response
    {
        $artists = $userRepository->findByRole('ROLE_ARTIST');
        return $this->render('front/default/artist/index.html.twig', [
            'artists' => $artists
        ]);
    }

    #[Route('/artist/{id}', name: 'default_artist')]
    public function getArtist(UserRepository $userRepository, $id, AuthorizationCheckerInterface $authorizationChecker): Response
    {
        $artist = $userRepository->find($id);

        $roles = $artist->getRoles();

        // Récupérer le rôle le plus haut
        $highestRole = '';
        foreach ($roles as $role) {
            if ($authorizationChecker->isGranted($role)) {
                $highestRole = $role;
                break;
            }
        }

        $music_groups = $artist->getMusicGroups();

        return $this->render('front/default/artist/show_artist.html.twig', [
            'artist' => $artist,
            'highestRole' => $highestRole,
            'music_groups' => $music_groups,
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

    #[Route('/email_template', name: 'default_email_template')]
    public function emailTemplate(MusicGroupRepository $musicGroupRepository): Response
    {
        return $this->render('front/email/registrationEmail.html.twig');
    }
}
