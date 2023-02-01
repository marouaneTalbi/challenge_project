<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Services\MailerService;

class SecurityController extends AbstractController
{

    private $mailer;

    public function __construct(MailerService $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // $mailer->sendMail();
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/register-choice', name: 'register-choice')]
    public function registerChoice(): Response
    {
        return $this->render('security/register-choice.html.twig', []);
    }

    #[Route(path: '/register/{type}', name: 'app_register')]
    public function register(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $encoder, $type): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType ::class, $user, [
            'is_artist' => $type == 'artist',
            'is_manager' => $type == 'manager',
        ]);
        $form->handleRequest($request);

        $adminUsers = $userRepository->findByRole('ROLE_ADMIN');

        if ($form->isSubmitted() && $form->isValid()) {
            $encoded = $encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($encoded);
            $user->setIsEnabled(false);
            $user->setIsDeleted(false);
            $user->setToken($this->generateToken());
            if ($type == 'artist') {
                $user->setStatus('waiting');
                $user->setApply('artist');
                foreach($adminUsers as $admin) {
                    $this->mailer->sendMailToAdmin($admin->getEmail());
                }
            }
            if ($type == 'manager') {
                $user->setStatus('waiting');
                $user->setApply('manager');
                foreach($adminUsers as $admin) {
                    $this->mailer->sendMailToAdmin($admin->getEmail());                
                }
            }
            $userRepository->save($user, true);
            
            $this->mailer->sendMail($user->getEmail(), $user->getToken());

            return $this->redirectToRoute('back_user_index', [], Response::HTTP_SEE_OTHER);
        }
        $this->mailer->sendMail($user->getEmail(), $user->getToken());

        return $this->render('security/register.html.twig', ['form' => $form->createView()]);
    }


    public function generateToken(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    #[Route(path: '/confirm/{token}', name: 'app_confirm')]
    public function confirm(string $token, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['token' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $user->setToken(null);
        $user->setIsEnabled(true);


        $userRepository->save($user, true);

        return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
    }

}
