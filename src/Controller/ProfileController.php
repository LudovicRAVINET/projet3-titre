<?php

namespace App\Controller;

use App\Entity\Avis;
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
     * @Route("/avis", name="avis", methods={"POST"})
     */
    public function avis(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser()->getLastname();

        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);
//dd($_POST);
        if (true) {
            $avis->setName($user);
            $avis->setComment($_POST['avis']['comment']);
            $avis->setNote($_POST['avis']['note']);
            $avis->setDate(new DateTime('now'));

            $entityManager->persist($avis);
            $entityManager->flush();

            // TODO : si la note est inférieure à 2 demander à l'utilisateur pourquoi

            $this->addFlash('success', 'Merci pour votre avis');

            $this->redirectToRoute('profile_index', [
                'id' => $this->getUser()->getId()
            ]);
        }

        return $this->render('component/_avis.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="index")
     */
    public function index(User $user): Response
    {
        $events = $user->getEvents();

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'events' => $events
        ]);
    }
}
