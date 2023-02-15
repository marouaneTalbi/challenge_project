<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class APIController extends AbstractController
{

    #[Route('/{id}/edit', name: 'app_api_id', methods: ['PUT'])]
    public function updateEvent(Event $event, Request $request, EventRepository $eventRepository): Response
    {
        $data = json_decode($request->getContent());
        if(
            isset($data->title) && !empty($data->title) &&
            isset($data->description) && !empty($data->description) &&
            isset($data->lieu) && !empty($data->lieu) &&
            isset($data->eventStart) && !empty($data->eventStart) &&
            isset($data->eventEnd) && !empty($data->eventEnd) &&
            isset($data->backgroundColor) && !empty($data->backgroundColor) &&
            isset($data->borderColor) && !empty($data->borderColor) &&
            isset($data->textColor) && !empty($data->textColor)
        ) {
            $code = 200;
            if(!$event) {
                $event = new Event;
                $code = 201;
            }
            $event->setTitle($data->title);
            $event->setDescription($data->description);
            $event->setLieu($data->lieu);
            $event->setEventStart(new DateTime($data->eventStart));
            $event->setEventEnd(new DateTime($data->eventEnd));
            $event->setBackgroundColor($data->backgroundColor);
            $event->setBorderColor($data->borderColor);
            $event->setTextColor($data->textColor);

            $eventRepository->save($event, true);

            return new Response('OK', $code);
        } else {
            dd($data);
            return new Response('Données incomplètes', 404);
        }

        return $this->render('api/index.html.twig', [
            'controller_name' => 'APIController',
        ]);
    }
}
