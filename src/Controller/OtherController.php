<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/other", name="other_")
*/
class OtherController extends AbstractController
{
     /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('other/index.html.twig');
    }
}
