<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile", name="profile_")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/{id}/{events}", name="index", methods={"POST","GET"})
     */
    public function index(Request $request, User $user): Response
    {
        $events = $user->getEvents();
        $param = $request->get('events');
        $type = $events->getType()->getName();
        $filtredEvents = [];

        dd($param);
        foreach($events as $event){
            if($param == ){

            }
        }
        //dd($events[0]->getType()->getName());

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'events' => $events
        ]);
    }
}
