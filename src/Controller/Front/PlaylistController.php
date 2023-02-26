<?php

namespace App\Controller\Front;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\MusicRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/playlist')]
class PlaylistController extends AbstractController
{
    #[Route('/', name: 'app_playlist_index', methods: ['GET'])]
    public function index(PlaylistRepository $playlistRepository): Response
    {
        return $this->render('front/playlist/index.html.twig', [
            'playlists' => $playlistRepository->findAll(),
        ]);

    }

    #[Route('/new', name: 'app_playlist_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlaylistRepository $playlistRepository, MusicRepository $musicRepository): Response
    {
        $playlist = new Playlist();
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd();
            // // $selectedUsers = $request->get('invites');
            // $music = $request->get('music');
            // foreach ($music as $key => $value) {
            //     $playlist->addMusic($musicRepository->find($value));
            // }
            $image = $form->get('image')->getData();
            $image->move($this->getParameter('images_directory'), $image->getClientOriginalName());
            $playlist->setImage($image->getClientOriginalName());

            $playlist->setOwner($this->getUser());
            $playlistRepository->save($playlist, true);

            return $this->redirectToRoute('front_app_playlist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/playlist/new.html.twig', [
            'playlist' => $playlist,
            'form' => $form,
            'musics' => $musicRepository->findAll(),
        ]);
    }




    #[Route('/{id}', name: 'app_playlist_show', methods: ['GET'])]
    public function show(Playlist $playlist, PlaylistRepository $playlistRepository): Response
    {
        // if(!$this->isGranted('ROLE_ADMIN') || $playlist->getOwner() != $this->getUser()){
        //     throw new AccessDeniedException();
        // }


        return $this->render('front/playlist/show.html.twig', [
            'playlist' => $playlist,
            'musics' => $playlist->getMusic()->toArray(),
        ]);
    }



    #[Route('/{id}/edit', name: 'app_playlist_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Playlist $playlist, PlaylistRepository $playlistRepository, MusicRepository $musicRepository): Response
    {
        if(!$this->isGranted('ROLE_ADMIN') && $playlist->getOwner() != $this->getUser()){
            throw new AccessDeniedException();
        }

        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $playlist->setOwner($this->getUser());
            $playlistRepository->save($playlist, true);

            return $this->redirectToRoute('front_app_playlist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/playlist/edit.html.twig', [
            'playlist' => $playlist,
            'form' => $form,
            'musics' => $playlist->getMusic()->toArray(),
        ]);
    }




    #[Route('/{id}', name: 'app_playlist_delete', methods: ['POST'])]
    public function delete(Request $request, Playlist $playlist, PlaylistRepository $playlistRepository): Response
    {
        if(!$this->isGranted('ROLE_ADMIN') && $playlist->getOwner() != $this->getUser()){
            throw new AccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$playlist->getId(), $request->request->get('_token'))) {
            $playlistRepository->remove($playlist, true);
        }

        return $this->redirectToRoute('front_app_playlist_index', [], Response::HTTP_SEE_OTHER);
    }




    #[Route('/show/my-play-list', name: 'app_show_myplaylist', methods: ['GET'])]
    public function myplaylist(PlaylistRepository $playlistRepository): Response
    {

         return $this->render('front/playlist/myplaylist.html.twig', [
             'playlists' => $playlistRepository->findBy(['owner' => $this->getUser()]),
         ]);
    }





    #[Route('/{id}/playlist-musics', name: 'app_music_myplaylist', methods: ['GET'])]
    public function myplaylistmusic( Playlist $playlist, PlaylistRepository $playlistRepository): Response
    {


        //dd($playlist->getMusic()->toArray());
        return $this->render('front/playlist/music_playlist.html.twig', [
            'allMusic' => $playlist->getMusic()->toArray(),
        ]);
    }


}
