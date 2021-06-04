<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/birthday", name="birthday_")
*/
class BirthdayController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('birthday/index.html.twig');
    }

    /**
     * @Route("/{id}/diary", name="diary")
     */
    public function diary(int $id): Response
    {
        return $this->render('birthday/diary.html.twig');
    }
}
