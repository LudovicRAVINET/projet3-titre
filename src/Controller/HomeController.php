<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="home_")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', ["home" => true]);
    }

    /**
     * @Route("/wedding", name="wedding")
     */
    public function indexWedding(): Response
    {
        return $this->render('wedding/index.html.twig', ["wedding" => true]);
    }

    /**
     * @Route("/birthday", name="birthday")
     */
    public function indexBirthday(): Response
    {
        return $this->render('birthday/index.html.twig', ["birthday" => true]);
    }

    /**
     * @Route("/birth", name="birth")
     */
    public function indexBirth(): Response
    {
        return $this->render('birth/index.html.twig', ["birth" => true]);
    }

    /**
     * @Route("/mourning", name="mourning")
     */
    public function indexMourning(): Response
    {
        return $this->render('mourning/index.html.twig', ["mourning" => true]);
    }

    /**
     * @Route("/other", name="other")
     */
    public function indexOther(): Response
    {
        return $this->render('other/index.html.twig', ["other" => true]);
    }
}
