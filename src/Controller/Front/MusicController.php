<?php

namespace App\Controller\Front;

use App\Entity\Music;
use App\Entity\MusicGroup;
use App\Form\MusicType;
use App\Repository\MusicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Security\Voter\MusicGroupArtistAccesVoter;
use App\Security\Voter\MusicGroupManagerAccesVoter;

#[Route('/music')]
class MusicController extends AbstractController
{
    #[Route('/', name: 'app_music_index', methods: ['GET'])]
    public function index(MusicRepository $musicRepository): Response
    {
        return $this->render('front/music/index.html.twig', [
            'music' => $musicRepository->findAll(),
        ]);
    }

    // #[Route('/new', name: 'app_music_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, MusicRepository $musicRepository): Response
    // {

    //     $music = new Music();

    //     $form = $this->createForm(MusicType::class, $music);
    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $mp3 = $form->get('url')->getData();
    //         $newMp3Name = str_replace(' ', '', $form->get('name')->getData());
    //         $mp3Name = md5(uniqid()).$newMp3Name.'.'.$mp3->guessExtension();
    //         $mp3->move($this->getParameter('upload_directory'), $mp3Name);
    //         $music->setUrl($mp3Name);
    //         $music->setSize(filesize($this->getParameter('upload_directory').'/'.$mp3Name).' Mo');

    //         $musicRepository->save($music, true);

    //         return $this->redirectToRoute('front_app_music_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('front/music/new.html.twig', [
    //         'music' => $music,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_music_show', methods: ['GET'])]
    public function show(Music $music): Response
    {
        return $this->render('front/music/show.html.twig', [
            'music' => $music,
        ]);
    }

    // #[Route('/{id}/edit', name: 'app_music_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Music $music, MusicRepository $musicRepository): Response
    // {
    //     $form = $this->createForm(MusicType::class, $music);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $musicRepository->save($music, true);

    //         return $this->redirectToRoute('front_app_music_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('front/music/edit.html.twig', [
    //         'music' => $music,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_music_delete', methods: ['POST'])]
    public function delete(Request $request, Music $music, MusicRepository $musicRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$music->getId(), $request->request->get('_token'))) {
            $musicRepository->remove($music, true);
        }

        return $this->redirectToRoute('front_app_music_index', [], Response::HTTP_SEE_OTHER);
    }
}
