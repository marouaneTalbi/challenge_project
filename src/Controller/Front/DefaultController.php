<?php

namespace App\Controller\Front;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_FAN')]
class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_index')]
    public function index(): Response
    {
        return $this->render('front/default/index.html.twig');
    }
}
