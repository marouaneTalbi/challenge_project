<?php

namespace App\Controller\Front;

use App\Entity\Music;
use App\Entity\MusicGroup;
use App\Form\MusicGroupType;
use App\Entity\User;
use App\Form\MusicType;
use App\Entity\NewsGroup;
use App\Repository\MusicGroupRepository;
use App\Repository\EventRepository;
use App\Repository\MusicRepository;
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

            // foreach ($selectedUsers as $userId) {
            //     $user = $userRepository->find($userId);
            //     $musicGroup->addArtiste($user);
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
    public function show(MusicGroup $musicGroup, MusicRepository $musicRepository): Response
    {
        $artist = $this->getUser();
        // if (!$this->isGranted(MusicGroupManagerAccesVoter::MANAGER_ACCESS, $musicGroup)) {
        //     throw new AccessDeniedException();
        // }
        $test = $musicGroup->getArtiste()->contains($artist);
        // if (!$musicGroup->getArtiste()->contains($artist)) {
        //     throw new AccessDeniedException("You are not a member of this group.");
        // }
        $albums = $musicGroup->getAlbums();
        $manager = $musicGroup->getManager();
        // dd($manager);

        $musics = $musicRepository->findBy(['owner_music_group' => $musicGroup->getId()]);

       // $musics = $musicGroup->getMusic()->toArray();

        return $this->render('front/music_group/show.html.twig', [
            'music_group' => $musicGroup,
            'musics' => $musics,
            'artists' => $musicGroup->getArtiste(),
            'news_group' => $musicGroup->getNewsGroups(),
            'albums' => $albums,
            'user' => $artist
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
            $selectedUsers = $request->get('artiste');
            
            $artists = $musicGroup->getArtiste();

            // foreach ($artists as $artist) {
            //     if (!in_array($artist, $selectedUsers)) {
            //         $musicGroup->removeArtiste($artist);
            //     }
            // }

            // foreach ($selectedUsers as $userId) {
            //     $user = $userRepository->find($userId);
            //     $musicGroup->addArtiste($user);
            // }
            // dd($form);
            // foreach($musicGroup->getArtiste() as $test){
            //     dd($test);
            // }
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

    #[Route('/{id}/new/music', name: 'app_music_new_for_groupmusic', methods: ['GET', 'POST'])]
    public function addMusicOfGroup(Request $request, MusicGroup $musicgroup, MusicRepository $musicRepository, MusicGroupRepository $musicGroupRepository): Response
    {
        //vérifie si le user est un artiste ou un manager
        if(!$this->isGranted('ROLE_ARTIST') && !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        //vérifie si le user est le manager ou un artiste du groupe
        if (!$this->isGranted(MusicGroupArtistAccesVoter::MEMBER_ACCESS, $musicgroup) &&
            !$this->isGranted(MusicGroupManagerAccesVoter::MANAGER_ACCESS, $musicgroup)) {
                throw new AccessDeniedException();
        }   

        $music = new Music();

        $form = $this->createForm(MusicType::class, $music);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $group = $musicGroupRepository->find(['id' => $request->attributes->get('id')]);
            $mp3 = $form->get('url')->getData();
            $newMp3Name = str_replace(' ', '', $form->get('name')->getData());
            $mp3Name = md5(uniqid()).$newMp3Name.'.'.$mp3->guessExtension();
            $mp3->move($this->getParameter('upload_directory'), $mp3Name);

            $music->setUrl($mp3Name);
            $music->setSize(filesize($this->getParameter('upload_directory').'/'.$mp3Name).' Mo');
            $music->setOwnerMusicGroup($group);

            $musicRepository->save($music, true);

            return $this->redirectToRoute('front_app_music_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/music/new.html.twig', [
            'music' => $music,
            'form' => $form,
        ]);
    }



    #[Route('/{id}/show-user', name: 'artist_show', methods: ['GET'])]
    public function showArtist(User $user): Response
    {

        return $this->render('front/default/artist/show_artist.html.twig', [
            'user' => $user,
            'musics' => $user->getMusic()->toArray(),
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
