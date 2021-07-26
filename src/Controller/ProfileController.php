<?php

namespace App\Controller;

use App\Entity\Notice;
use App\Entity\User;
use App\Form\NoticeType;
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
            'events' => $eventToDisplay,
            'eventType' => $request->get('eventType')
        ]);
    }

    /**
     * @Route("/notice/user/{id}", name="notice", methods={"POST"})
     */
    public function notice(Request $request, EntityManagerInterface $entityManager, User $user): Response
    {
        $avis = new Notice();

        $avis->setName($user->getLastname());
        $avis->setComment($_POST['notice']['comment']);
        $avis->setNote($_POST['notice']['note']);
        $avis->setDate(new DateTime('now'));

        $entityManager->persist($avis);
        $entityManager->flush();


        // TODO : si la note est inférieure à 2 demander à l'utilisateur pourquoi

        $this->addFlash('success', 'Merci pour votre avis');

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'events' => $user->getEvents()
        ]);
    }


    /**
     * @Route("/notice/user/{id}", name="noticeShow", methods={"GET"})
     */
    public function noticeShow(Request $request, EntityManagerInterface $entityManager, User $user): Response
    {
        $avis = new Notice();
        $form = $this->createForm(NoticeType::class, $avis);

        return $this->render('component/_avis.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
