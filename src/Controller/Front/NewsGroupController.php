<?php

namespace App\Controller\Front;

use App\Entity\NewsGroup;
use App\Form\NewsGroupType;
use App\Repository\NewsGroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/news/group')]
class NewsGroupController extends AbstractController
{
    #[Route('/', name: 'app_news_group_index', methods: ['GET'])]
    public function index(NewsGroupRepository $newsGroupRepository): Response
    {
        return $this->render('front/news_group/index.html.twig', [
            'news_groups' => $newsGroupRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_news_group_new', methods: ['GET', 'POST'])]
    public function new(Request $request, NewsGroupRepository $newsGroupRepository): Response
    {
        $newsGroup = new NewsGroup();
        $form = $this->createForm(NewsGroupType::class, $newsGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newsGroupRepository->save($newsGroup, true);

            return $this->redirectToRoute('app_news_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/news_group/new.html.twig', [
            'news_group' => $newsGroup,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_news_group_show', methods: ['GET'])]
    public function show(NewsGroup $newsGroup): Response
    {
        return $this->render('front/news_group/show.html.twig', [
            'news_group' => $newsGroup,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_news_group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, NewsGroup $newsGroup, NewsGroupRepository $newsGroupRepository): Response
    {
        $form = $this->createForm(NewsGroupType::class, $newsGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newsGroupRepository->save($newsGroup, true);

            return $this->redirectToRoute('app_news_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/news_group/edit.html.twig', [
            'news_group' => $newsGroup,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_news_group_delete', methods: ['POST'])]
    public function delete(Request $request, NewsGroup $newsGroup, NewsGroupRepository $newsGroupRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$newsGroup->getId(), $request->request->get('_token'))) {
            $newsGroupRepository->remove($newsGroup, true);
        }

        return $this->redirectToRoute('app_news_group_index', [], Response::HTTP_SEE_OTHER);
    }
}
