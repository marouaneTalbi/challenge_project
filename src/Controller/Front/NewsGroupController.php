<?php

namespace App\Controller\Front;

use App\Entity\NewsGroup;
use App\Entity\MusicGroup;
use App\Repository\MusicGroupRepository;
use App\Form\NewsGroupType;
use App\Repository\NewsGroupRepository;
use App\Repository\UserRepository;
use App\Services\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Security\Voter\MusicGroupManagerAccesVoter;
use Symfony\Component\Finder\Exception\AccessDeniedException;

#[Route('/news/group')]
class NewsGroupController extends AbstractController
{
    private $mailer;

    public function __construct(MailerService $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route('/', name: 'app_news_group_index', methods: ['GET'])]
    public function index(NewsGroupRepository $newsGroupRepository): Response
    {
        return $this->render('front/news_group/index.html.twig', [
            'news_groups' => $newsGroupRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_news_group_new', methods: ['GET', 'POST'])]
    public function new(Request $request, NewsGroupRepository $newsGroupRepository, $id, MusicGroupRepository $musicGroupRepository, UserRepository $userRepository): Response
    {
        $userRole = $this->getUser()->getRoles();
        $musicGroup = $musicGroupRepository->find($id);

        if (!$this->isGranted(MusicGroupManagerAccesVoter::MANAGER_ACCESS, $musicGroup) && !in_array("ROLE_ADMIN", $userRole)) {
            throw new AccessDeniedException();
        }else{
            $newsGroup = new NewsGroup();
            $form = $this->createForm(NewsGroupType::class, $newsGroup);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $newsGroup->setStatus("waiting");
                $userId = (string)$this->getUser()->getId();
                $newsGroup->setAuthor($this->getUser());
                $newsGroup->setGroupe($musicGroup);
                $newsGroupRepository->save($newsGroup, true);

                $adminUsers = $userRepository->findByRole('ROLE_ADMIN');

                foreach($adminUsers as $admin) {
                    $this->mailer->sendMailToAdmin($admin->getEmail());
                }

                return $this->redirectToRoute('front_app_news_group_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('front/news_group/new.html.twig', [
                'news_group' => $newsGroup,
                'form' => $form,
            ]);
        }
    }

    #[Route('/{id}', name: 'app_news_group_show', methods: ['GET'])]
    public function show(NewsGroup $newsGroup): Response
    {
        return $this->render('front/news_group/show.html.twig', [
            'news_group' => $newsGroup,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_news_group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, NewsGroup $newsGroup, MusicGroupRepository $musicGroupRepository, NewsGroupRepository $newsGroupRepository, $id): Response
    {
        $userRole = $this->getUser()->getRoles();
        $news = $newsGroupRepository->find($id);
        $musicGroup = $musicGroupRepository->find($news->getGroupe());

        if (!$this->isGranted(MusicGroupManagerAccesVoter::MANAGER_ACCESS, $musicGroup) && !in_array("ROLE_ADMIN", $userRole)) {
            throw new AccessDeniedException();
        }else {
            $form = $this->createForm(NewsGroupType::class, $newsGroup);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $newsGroupRepository->save($newsGroup, true);

                return $this->redirectToRoute('front_app_news_group_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('front/news_group/edit.html.twig', [
                'news_group' => $newsGroup,
                'form' => $form,
            ]);
        }
    }

    #[Route('/delete/{id}', name: 'app_news_group_delete', methods: ['GET'])]
    public function delete(Request $request, NewsGroup $newsGroup, NewsGroupRepository $newsGroupRepository, MusicGroupRepository $musicGroupRepository, $id): Response
    {
        $userRole = $this->getUser()->getRoles();
        $news = $newsGroupRepository->find($id);
        $musicGroup = $musicGroupRepository->find($news->getGroupe());

        if (!$this->isGranted(MusicGroupManagerAccesVoter::MANAGER_ACCESS, $musicGroup) && !in_array("ROLE_ADMIN", $userRole)) {
            throw new AccessDeniedException();
        }else {
            //if ($this->isCsrfTokenValid('delete' . $news->getId(), $request->request->get('_token'))) {
                $newsGroupRepository->remove($news, true);
            //}
        }

        return $this->redirectToRoute('front_app_news_group_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/valid/{id}', name: 'app_news_valid', methods: ['GET'])]
    public function validNews($id, NewsGroupRepository $newsGroupRepository): Response
    {
        $userRole = $this->getUser()->getRoles();

        if(in_array("ROLE_ADMIN", $userRole)) {
            $news = $newsGroupRepository->find($id);
            $news->setStatus("valid");
            $newsGroupRepository->save($news, true);
        }else{
            throw new AccessDeniedException();
        }

        return $this->redirectToRoute('back_mailbox_index', [], Response::HTTP_SEE_OTHER);
    }
}
