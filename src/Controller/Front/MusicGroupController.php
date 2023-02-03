<?php

namespace App\Controller\Front;

use App\Entity\MusicGroup;
use App\Form\MusicGroupType;
use App\Entity\User;
use App\Repository\MusicGroupRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/music-group')]
class MusicGroupController extends AbstractController
{
    #[Route('/', name: 'app_music_group_index', methods: ['GET'])]
    public function index(MusicGroupRepository $musicGroupRepository): Response
    {
        return $this->render('front/music_group/index.html.twig', [
            'music_groups' => $musicGroupRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_music_group_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MusicGroupRepository $musicGroupRepository, UserRepository $userRepository): Response
    {
        $musicGroup = new MusicGroup();
        $form = $this->createForm(MusicGroupType::class, $musicGroup);
        $form->handleRequest($request);
        
        $artistUsers = $userRepository->findByRole('ROLE_ARTIST');


        if ($form->isSubmitted() && $form->isValid()) {

            $artist = $form->getData()->getArtiste();
            foreach($artist as $user) {
                if($user->getRoles() != "ROLE_ARTIST") {

                }
            }

            // if($this->getUser()->getRoles() != "ROLE_MANAGER") {
            //     return $this->redirectToRoute('front_app_music_group_index', [], Response::HTTP_UNAUTHORIZED);
            // }

            $musicGroup->setManager($this->getUser());
            $musicGroupRepository->save($musicGroup, true);

            return $this->redirectToRoute('front_app_music_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/music_group/new.html.twig', [
            'music_group' => $musicGroup,
            'form' => $form,
            'artistUsers' => $artistUsers
        ]);
    }

    #[Route('/{id}', name: 'app_music_group_show', methods: ['GET'])]
    public function show(MusicGroup $musicGroup): Response
    {
        return $this->render('front/music_group/show.html.twig', [
            'music_group' => $musicGroup,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_music_group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MusicGroup $musicGroup, MusicGroupRepository $musicGroupRepository): Response
    {
        $form = $this->createForm(MusicGroupType::class, $musicGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $musicGroupRepository->save($musicGroup, true);

            return $this->redirectToRoute('front_app_music_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/music_group/edit.html.twig', [
            'music_group' => $musicGroup,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_music_group_delete', methods: ['POST'])]
    public function delete(Request $request, MusicGroup $musicGroup, MusicGroupRepository $musicGroupRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$musicGroup->getId(), $request->request->get('_token'))) {
            $musicGroupRepository->remove($musicGroup, true);
        }

        return $this->redirectToRoute('front_app_music_group_index', [], Response::HTTP_SEE_OTHER);
    }
}
