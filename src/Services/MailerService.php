<?php
namespace App\Services;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class MailerService extends AbstractController
{

    private $mailer;
    
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;

    }

    public function sendMailToAdmin($email): Response
    {
        $email = (new TemplatedEmail())
            ->from('challenge.noreply@gmail.com')
            ->to(new Address($email))
            ->subject('Hello Email')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>')
            ->htmlTemplate('front/email/update-status.html.twig');


            $this->mailer->send($email);

            return $this->render('front/default/index.html.twig');
    }

    public function sendMail($email, $token) :Response
    {
       $email = (new TemplatedEmail())
            ->from('challenge.noreply@gmail.com')
            ->to(new Address($email))
            ->subject('Hello Email')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>')
            ->htmlTemplate('front/email/registrationEmail.html.twig')
            ->context([
                'token' => $token,
            ]);

            $this->mailer->send($email);

            return $this->render('front/default/index.html.twig');
    }
}