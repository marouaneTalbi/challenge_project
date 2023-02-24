<?php

namespace App\Controller\Front;

use App\Entity\MusicGroup;
use App\Entity\Album;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use App\Repository\MusicGroupRepository;
use App\Repository\MusicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// #[Route('/album')]
class AlbumController extends AbstractController
{
    #[Route('/album', name: 'app_album_index', methods: ['GET'])]
    public function index(AlbumRepository $albumRepository): Response
    {
        return $this->render('front/album/index.html.twig', [
            'albums' => $albumRepository->findAll(),
        ]);
    }




    #[Route('/music_group/{id}/album/new', name: 'app_album_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AlbumRepository $albumRepository, MusicGroup $musicGroup, MusicGroupRepository $musicGroupRepository, MusicRepository $musicRepository): Response
    {
        $album = new Album();
        $musics = $musicGroup->getMusic();

        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $album->setMusicGroup($musicGroup);
            $selectedMusics = $request->get('musics');
            foreach ($selectedMusics as $selectedMusic) {
                $music = $musicRepository->find($selectedMusic);
                $music->setAlbum($album);
                $musicRepository->save($music, true);
            }
            $image = $form->get('image')->getData();
            $image->move($this->getParameter('images_directory'), $image->getClientOriginalName());
            $album->setImage($image->getClientOriginalName());
            $albumRepository->save($album, true);
        
            return $this->redirectToRoute('front_app_music_group_show', ['id' => $musicGroup->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/album/new.html.twig', [
            'album' => $album,
            'form' => $form,
            'musics' => $musics,
        ]);
    }




    #[Route('/album/{id}', name: 'app_album_show', methods: ['GET'])]
    public function show(Album $album): Response
    {
        $musics = $album->getMusic();
        $musicList = [];
        foreach ($musics as $music) {
            $musicList[] = $music;
        }
        
        return $this->render('front/album/show.html.twig', [
            'album' => $album,
            'musicList' => $musicList,
        ]);
    }




    #[Route('/album/{id}/edit', name: 'app_album_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Album $album, AlbumRepository $albumRepository): Response
    {
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $albumRepository->save($album, true);

            return $this->redirectToRoute('app_album_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/album/edit.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }

    #[Route('/album/{id}', name: 'app_album_delete', methods: ['POST'])]
    public function delete(Request $request, Album $album, AlbumRepository $albumRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$album->getId(), $request->request->get('_token'))) {
            $albumRepository->remove($album, true);
        }

        return $this->redirectToRoute('app_album_index', [], Response::HTTP_SEE_OTHER);
    }
}
