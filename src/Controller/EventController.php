<?php

namespace App\Controller;

use DateTime;
use App\Entity\Wedding;
use App\Entity\Birthday;
use App\Entity\Mourning;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="new_event", methods={"POST"})
     */
    public function createEvent(Request $request, EntityManagerInterface $manager): Response
    {
        $eventType = htmlentities(trim($request->get('event_type')));
        $eventName = htmlentities(trim($request->get('event_name')));
        $eventDate = htmlentities(trim($request->get('event_date')));

        if ($eventType == 'wedding') {
            $wedding = new Wedding();
            $wedding->setEventName($eventName)
                ->setEventDate(new DateTime($eventDate))
                ->setEventCreatedAt(new DateTime('now'));
            $manager->persist($wedding);
        } elseif ($eventType == 'birthday') {
            $birthday = new Birthday();
            $birthday->setEventName($eventName)
                ->setEventDate(new DateTime($eventDate))
                ->setEventCreatedAt(new DateTime('now'));
            $manager->persist($birthday);
        } elseif ($eventType == 'mourning') {
            $mourning = new Mourning();
            $mourning->setEventName($eventName)
                ->setEventDate(new DateTime($eventDate))
                ->setEventCreatedAt(new DateTime('now'));
            $manager->persist($mourning);
        } else {
            $this->addFlash('danger', "Veuillez selectionner un événement.");
            return $this->redirectToRoute('home_index');
        }

        $manager->flush();

        $this->addFlash('success', 'Votre événement a bien été créé.');
        return $this->redirectToRoute('home_index');
    }
}
