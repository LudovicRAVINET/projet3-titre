<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/birth", name="birth_")
*/
class BirthController extends AbstractController
{
     /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('birth/index.html.twig');
    }
}
