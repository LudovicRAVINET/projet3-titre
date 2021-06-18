<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/subscription", name="subscription_")
*/
class SubscriptionController extends AbstractController
{
    /**
     * @Route("/choice/{id}", name="choice")
     */
    public function choice(): Response
    {
        return $this->render('home/index.html.twig');
    }
}