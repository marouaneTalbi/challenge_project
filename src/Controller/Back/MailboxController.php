<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Entity\NewsGroup;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/mailbox')]
class MailboxController extends AbstractController
{

    #[Route('/', name: 'mailbox_index', methods: ['GET'])]
    public function index(EntityManagerInterface $manager, ManagerRegistry $repository): Response
    {
        $registry = $repository->getRepository(User::class);
        $news = $repository->getRepository(NewsGroup::class);

        $artistsUsersRequest = $registry->findBy(['status' => 'waiting', 'apply' => 'artist']);
        $managersUsersRequest = $registry->findBy(['status' => 'waiting', 'apply' => 'manager']);
        $newsInWaiting = $news->findBy(['status' => 'waiting']);

        return $this->render('back/mailbox/index.html.twig', [
            'artistsUsersRequest' => $artistsUsersRequest,
            'managersUsersRequest' => $managersUsersRequest,
            'newsInWaiting' => $newsInWaiting
        ]);
    }


}
