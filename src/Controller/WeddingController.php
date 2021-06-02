<?php

namespace App\Controller;

use App\Entity\Wedding;
use App\Form\WeddingType;
use App\Repository\WeddingRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\EventRepository;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
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
        Request $request,
        FileUploader $fileUploader
    ): Response {
        $message = new Message();
        $message->setMessageDateTime(new DateTime('now'));
        $message->setEventId($eventRepository->find($id));

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $mediaUrlFile */
            $mediaUrlFile = $form->get('mediaUrl')->getData();
            if (!empty($mediaUrlFile)) {
                $mediaUrlFileName = $fileUploader->upload($mediaUrlFile);
                $message->setMediaUrl($mediaUrlFileName);
            }

            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('wedding_diary', ['id' => $id]);
        }

        return $this->render('wedding/diary.html.twig', [
            'message' => $message,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/createDiary", name="create_diary")
     */
    public function createDiary(
        int $id,
        Request $request,
        WeddingRepository $weddingRepository,
        EntityManagerInterface $manager,
        FileUploader $fileUploader
    ): Response {
        $wedding = $weddingRepository->find($id);
        $form = $this->createForm(WeddingType::class, $wedding);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $eventPicture */
            $eventPicture = $form->get('eventPicture')->getData();
            if (!empty($wedding)) {
                $eventPictureFileName = $fileUploader->upload($eventPicture);
                $wedding->setEventPicture($eventPictureFileName);

                $manager->persist($wedding);
                $manager->flush();
            }

            return $this->redirectToRoute('wedding_index');
        }


        return $this->render('wedding/createDiary.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
