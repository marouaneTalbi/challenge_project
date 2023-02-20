<?php

namespace App\Controller\Front;

use App\Entity\MusicGroup;
use App\Form\MusicGroupType;
use App\Entity\User;
use App\Repository\MusicGroupRepository;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Security\Voter\MusicGroupArtistAccesVoter;
use App\Security\Voter\MusicGroupManagerAccesVoter;
use App\Security\Voter\MusicGroupVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
        //vérifie si le user est un manager
        $this->denyAccessUnlessGranted('ROLE_MANAGER');
        
        $musicGroup = new MusicGroup();
        $form = $this->createForm(MusicGroupType::class, $musicGroup);
        $form->handleRequest($request);
        
        $artistUsers = $userRepository->findByRole('ROLE_ARTIST');

        if ($form->isSubmitted() && $form->isValid()) {

            $selectedUsers = $request->get('artiste');

            foreach ($selectedUsers as $userId) {
                $user = $userRepository->find($userId);
                $musicGroup->addArtiste($user);
            }

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
        $artist = $this->getUser();
        // if (!$this->isGranted(MusicGroupManagerAccesVoter::MANAGER_ACCESS, $musicGroup)) {
        //     throw new AccessDeniedException();
        // }
        $test = $musicGroup->getArtiste()->contains($artist);
        // if (!$musicGroup->getArtiste()->contains($artist)) {
        //     throw new AccessDeniedException("You are not a member of this group.");
        // }

        return $this->render('front/music_group/show.html.twig', [
            'music_group' => $musicGroup,
        ]);
    }


    
    #[Route('/{id}/edit', name: 'app_music_group_edit', methods: ['GET', 'POST'])]
    public function edit(UserRepository $userRepository, Request $request, MusicGroup $musicGroup, MusicGroupRepository $musicGroupRepository): Response
    {
        //vérifie si le user est un manager
        $this->denyAccessUnlessGranted('ROLE_MANAGER');

        //vérifie si le user est le manager du groupe 
        if (!$this->isGranted(MusicGroupManagerAccesVoter::MANAGER_ACCESS, $musicGroup)) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(MusicGroupType::class, $musicGroup);
        $form->handleRequest($request);

        $artistUsers = $userRepository->findByRole('ROLE_ARTIST');

        if ($form->isSubmitted() && $form->isValid()) {
            $musicGroupRepository->save($musicGroup, true);

            return $this->redirectToRoute('front_app_music_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/music_group/edit.html.twig', [
            'music_group' => $musicGroup,
            'form' => $form,
            'artistUsers' => $artistUsers,
        ]);
    }



    #[Route('/{id}', name: 'app_music_group_delete', methods: ['POST'])]
    public function delete(Request $request, MusicGroup $musicGroup, MusicGroupRepository $musicGroupRepository): Response
    {

        //vérifie si le user est un manager
        $this->denyAccessUnlessGranted('ROLE_MANAGER');

        //vérifie si le user est le manager du groupe 
        if (!$this->isGranted(MusicGroupManagerAccesVoter::MANAGER_ACCESS, $musicGroup)) {
            throw new AccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$musicGroup->getId(), $request->request->get('_token'))) {

            $musicGroupRepository->remove($musicGroup, true);
        }

        return $this->redirectToRoute('front_app_music_group_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/calendar', name: 'app_music_group_calendar', methods: ['GET'])]
    public function calendar(MusicGroup $musicGroup, MusicGroupRepository $musicGroupRepository, EventRepository $eventRepository, $id): Response
    {
        //vérifie si le user est un artiste ou un manager
        if(!$this->isGranted('ROLE_ARTIST') && !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        //vérifie si le user est le manager ou un artiste du groupe
        if (!$this->isGranted(MusicGroupArtistAccesVoter::MEMBER_ACCESS, $musicGroup) &&
            !$this->isGranted(MusicGroupManagerAccesVoter::MANAGER_ACCESS, $musicGroup)) {
                throw new AccessDeniedException();
        }    
        
        $events = $eventRepository->findBy(['music_group' => $id]);
        $rdvs = [];
        foreach ($events as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'title' => $event->getTitle(),
                'descirption' => $event->getDescription(),
                'lieu' => $event->getLieu(),
                'start' => $event->getEventStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEventEnd()->format('Y-m-d H:i:s'),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor()
            ];
        }
        $data = json_encode($rdvs);

        return $this->render('front/music_group/calendrier.html.twig', [
            'music_groups' => $musicGroupRepository->findAll(),
            'data' => $data,
            'music_group_id' => $id,
        ]);
    }


    
    #[Route('/{id}/my-event', name: 'app_music_group_event', methods: ['GET'])]
    public function event(MusicGroup $musicGroup, MusicGroupRepository $musicGroupRepository, EventRepository $eventRepository, $id): Response
    {
        //vérifie si le user est un artiste ou un manager
        if(!$this->isGranted('ROLE_ARTIST') && !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        //vérifie si le user est le manager ou un artiste du groupe
        if (!$this->isGranted(MusicGroupArtistAccesVoter::MEMBER_ACCESS, $musicGroup) &&
            !$this->isGranted(MusicGroupManagerAccesVoter::MANAGER_ACCESS, $musicGroup)) {
                throw new AccessDeniedException();
        }

        $events = $eventRepository->findBy(['music_group' => $id]);

        return $this->render('front/music_group/event.html.twig', [
            'music_groups' => $musicGroupRepository->findAll(),
            'events' => $events,
        ]);
    }

}
