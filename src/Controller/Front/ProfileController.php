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


class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(MusicGroupRepository $musicGroupRepository, Request $request,UserRepository $userRepository, UserPasswordHasherInterface $encoder): Response
    {

        $user = $this->getUser();
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
        ]);
    }


}