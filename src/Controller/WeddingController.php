<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

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
     * @Route("/{id}/diary", name="diary", methods={"GET","POST"})
     */
    public function diary(
        int $id,
        EventRepository $eventRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $message = new Message();
        $message->setMessageDateTime(new DateTime('now'));
        $message->setEventId($eventRepository->find($id));

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('wedding_diary', ['id' => $id]);
        }

        return $this->render('wedding/diary.html.twig', [
            'message' => $message,
            'form' => $form->createView()
        ]);
    }
}
