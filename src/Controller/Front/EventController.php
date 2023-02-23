<?php

namespace App\Controller\Front;

use App\Entity\Event;
use App\Form\EventType;
use App\Entity\EventUser;
use App\Repository\EventRepository;
use App\Repository\MusicGroupRepository;
use App\Repository\UserRepository;
use App\Security\Voter\EventVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\ORM\EntityManagerInterface;


#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findBy(['public' => true]);

        return $this->render('front/event/index.html.twig', [
            'events' => $events,
        ]);
    }



    #[Route('/{id}/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, MusicGroupRepository $musicGroupRepository, EventRepository $eventRepository, $id): Response
    {
        $event = new Event();
        $musicGroup = $musicGroupRepository->find($id);
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        $artistUsers = $userRepository->findByRole('ROLE_FAN');

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setMusicGroup($musicGroup);

            $selectedUsers = $request->get('invites');
            foreach ($selectedUsers as $userId) {
                $user = $userRepository->find($userId);
                $event->addInvite($user);
            }
            $eventRepository->save($event, true);

            return $this->redirectToRoute('front_app_music_group_calendar', ["id" => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/event/new.html.twig', [
            'event' => $event,
            'form' => $form,
            'artistUsers' => $artistUsers
        ]);
    }



    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        if (!$this->isGranted(EventVoter::EVENT_ACCESS, $event)) {
            throw new AccessDeniedException();
        }
        $invites = $event->getInvite();
        // dd($invites);
        return $this->render('front/event/show.html.twig', [
            'event' => $event,
            'invites' => $invites,
        ]);
    }



    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EventRepository $eventRepository, UserRepository $userRepository): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        $artistUsers = $userRepository->findByRole('ROLE_FAN');

        if (!$this->isGranted(EventVoter::EVENT_ACCESS, $event)) {
            throw new AccessDeniedException();
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->save($event, true);

            return $this->redirectToRoute('front_app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
            'artistUsers' => $artistUsers,
        ]);
    }



    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $eventRepository->remove($event, true);
        }

        return $this->redirectToRoute('front_app_event_index', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('/{id}/toggle-publicity', name: 'app_event_toggle_publicity', methods: ['GET', 'POST'])]
    public function togglePublicity(Event $event, EventRepository $eventRepository): Response
    {

        if (!$this->isGranted(EventVoter::EVENT_ACCESS, $event)) {
            throw new AccessDeniedException();
        }
        
        // update the event
        if ($event->isPublic()) {
            $event->setPublic(false);
            $eventRepository->save($event, true);
        } else {
            $event->setPublic(true);
            $eventRepository->save($event, true);
        }


        return $this->redirectToRoute('front_app_event_show', ['id' => $event->getId()]);
    }
}
