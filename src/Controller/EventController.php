<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\TypeRepository;
use DateTime;
use Doctrine\DBAL\Types\TypeRegistry;
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
    public function createEvent(
        Request $request,
        EntityManagerInterface $manager,
        TypeRepository $typeRepository
    ): Response {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        /** @var \App\Entity\Type $eventType */
        $eventType = htmlentities(trim($request->get('event_type')));
        $eventName = htmlentities(trim($request->get('event_name')));
        $eventDate = htmlentities(trim($request->get('event_date')));
        $hasJackpot = htmlentities(trim($request->get('jackpot')));

        if ($eventType != null) {
            $event = new Event();

            /** @var \App\Entity\Type $type */
            $type = $typeRepository->findOneBy([
                'name' => $eventType
            ]);

            $event->setTitle($eventName)
                ->setType($type)
                ->setDate(new DateTime($eventDate))
                ->setUser($user);

            if ($hasJackpot === "on") {
                $event->setHasJackpot(true);
            }

            $manager->persist($event);
        } else {
            $this->addFlash('danger', "Veuillez selectionner un événement.");
            return $this->redirectToRoute('home_index');
        }

        $manager->flush();

        $this->addFlash('success', 'Votre événement a bien été créé.');
        return $this->redirectToRoute('home_index');
    }
}
