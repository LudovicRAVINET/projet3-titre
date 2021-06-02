<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/mourning", name="mourning_")
*/
class MourningController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('mourning/index.html.twig', [
            'controller_name' => 'MourningController',
        ]);
    }

    /**
     * @Route("/{id}/diary", name="diary")
     */
    public function diary(int $id): Response
    {
        return $this->render('mourning/diary.html.twig');
    }
}
