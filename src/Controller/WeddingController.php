<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/wedding", name="wedding_")
*/
class WeddingController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('wedding/index.html.twig');
    }

    /**
     * @Route("/{id}/diary", name="diary")
     */
    public function diary(int $id): Response
    {
        return $this->render('wedding/diary.html.twig');
    }
}
