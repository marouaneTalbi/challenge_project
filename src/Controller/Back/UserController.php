<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Services\MailerService;


#[Route('/user')]
class UserController extends AbstractController
{
    private $mailer;

    public function __construct(MailerService $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route('/', name: 'user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        // dd($userRepository->findAll());
        return $this->render('back/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $encoded = $encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($encoded);
            $user->setIsEnabled(true);
            $user->setIsDeleted(false);


            $userRepository->save($user, true);

            return $this->redirectToRoute('back_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('back/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $encoder): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encoded = $encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($encoded);
            $userRepository->save($user, true);

            return $this->redirectToRoute('back_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('back_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/update-status/{role}', name: 'user_update_status', methods: ['POST'])]
    public function updateStatus($id, UserRepository $userRepository, $role): Response
    {
        $user = $userRepository->find($id);
        $apply = $user->getApply();

        $message = "Bravo, tu as obtenue le grade ".$role;
        $this->mailer->sendMailRoleToUser($user->getEmail(), $message);

        if ($apply == "artist") {
            $user->setRoles(['ROLE_ARTIST']);
        }
        if ($apply == "manager") {
            $user->setRoles(['ROLE_MANAGER']);
        }
        $user->setApply(null);
        $user->setStatus("accepted");
        $userRepository->save($user, true);

        return $this->redirectToRoute('back_mailbox_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/rejected-status/{role}', name: 'user_rejected_status', methods: ['POST'])]
    public function rejectedStatus($id, UserRepository $userRepository, $role): Response
    {
        $user = $userRepository->find($id);
        $message = "Désolé, tu n'est pas qualifié pour devenir ".$role;
        $this->mailer->sendMailRoleToUser($user->getEmail(), $message);

        $user->setApply(null);
        $user->setStatus("rejected");
        $userRepository->save($user, true);

        return $this->redirectToRoute('back_mailbox_index', [], Response::HTTP_SEE_OTHER);
    }
}
