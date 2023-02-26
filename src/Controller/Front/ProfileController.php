<?php

namespace App\Controller\Front;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MusicGroupRepository;
use App\Repository\EventRepository;
use App\Entity\User;
use App\Form\ProfileFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;



class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(MusicGroupRepository $musicGroupRepository, Request $request,UserRepository $userRepository, UserPasswordHasherInterface $encoder, AuthorizationCheckerInterface $authorizationChecker): Response
    {
        
        $user = $this->getUser();

        $roles = $user->getRoles();

        // Récupérer le rôle le plus haut
        $highestRole = '';
        foreach ($roles as $role) {
            if ($authorizationChecker->isGranted($role)) {
                $highestRole = $role;
                break;
            }
        }
        $musicGroups = $musicGroupRepository->findBy(['manager' => $user->getId()]);
        $user = $this->getUser();
        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

           // dd($form->get('image')->getData());
            $image = $form->get('image')->getData();
           // $newImageName = str_replace(' ', '', $form->get('image')->getData());
            $image->move($this->getParameter('images_directory'), $image->getClientOriginalName());

            $user->setImage($image->getClientOriginalName());

            $userRepository->save($user, true);

             return $this->redirectToRoute('front_app_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/profile/index.html.twig', [
            'form' => $form->createView(),
            'music_groups' => $musicGroups,
            'user' => $user,
            'highestRole' => $highestRole,
        ]);
    }




    #[Route('/profile/edit', name: 'app_profile_edit')]
    public function edit(UserRepository $userRepository, Request $request): Response
    {

        $user = $this->getUser();

        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // dd($form->get('image')->getData());
            $image = $form->get('image')->getData();
            // $newImageName = str_replace(' ', '', $form->get('image')->getData());
            $image->move($this->getParameter('images_directory'), $image->getClientOriginalName());

            $user->setImage($image->getClientOriginalName());

            $userRepository->save($user, true);

            return $this->redirectToRoute('front_app_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/profile/edit.html.twig', [
            'form' => $form->createView(),
            'error' => null,
        ]);
    }




    #[Route('/profile/music_group', name: 'app_profile_music_group')]
    public function musicGroup(UserRepository $userRepository, Request $request): Response
    {
        $user = $this->getUser();
        if ($this->isGranted('ROLE_MANAGER')) {
            $musicGroups = $user->getManagerMusicGroups();
        }
        if ($this->isGranted('ROLE_ARTIST')) {
            $musicGroups = $user->getMusicGroups();
        }

        return $this->render('front/profile/music_group.html.twig', [
            'music_groups' => $musicGroups,
        ]);
    }



    #[Route('/profile/events', name: 'app_profile_events')]
    public function events(UserRepository $userRepository, Request $request): Response
    {
        $user = $this->getUser();

        $events = $user->getEvents();

        return $this->render('front/profile/event.html.twig', [
            'events' => $events,
            // 'music_group' => $musicGroups,
        ]);
    }


}