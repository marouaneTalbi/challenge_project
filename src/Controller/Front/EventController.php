<?php

namespace App\Controller\Front;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\MusicGroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('front/event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }


    
    #[Route('/{id}/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MusicGroupRepository $musicGroupRepository, EventRepository $eventRepository, $id): Response
    {
        $event = new Event();
        $musicGroup = $musicGroupRepository->find($id);
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setMusicGroup($musicGroup);
            $eventRepository->save($event, true);

            return $this->redirectToRoute('front_app_music_group_calendar', ["id" => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }



    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('front/event/show.html.twig', [
            'event' => $event,
        ]);
    }



    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->save($event, true);

            return $this->redirectToRoute('front_app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
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
}