<?php

namespace App\Controller;

use App\Entity\Notice;
use App\Entity\User;
use App\Form\AvisType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile", name="profile_")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/{id}/{eventType}", defaults={"eventType"="all"}, name="index", methods={"POST","GET"})
     */
    public function index(Request $request, User $user): Response
    {
        $events = $user->getEvents();
        $eventToDisplay = [];


        foreach ($events as $event) {
            $type = $event->getType();

            if ($type != null) {
                if (($request->get('eventType') == $type->getName())) {
                    $eventToDisplay[] = $event;
                }
            }
        }

        if ($request->get('eventType') == "all") {
            $eventToDisplay = $events;
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'events' => $eventToDisplay
        ]);
    }
}
