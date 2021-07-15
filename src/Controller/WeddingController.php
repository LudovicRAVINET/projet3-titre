<?php

namespace App\Controller;

use App\Entity\Wedding;
use App\Form\WeddingType;
use App\Repository\WeddingRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\EventRepository;
use App\Repository\MessageRepository;
use App\Service\BannerUpdate;
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
        MessageRepository $messageRepository,
        WeddingRepository $weddingRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        FileUploader $fileUploader
    ): Response {

        /* //// Message Form //// */
        $event = $weddingRepository->find($id);
        $message = new Message();
        $message->setMessageDateTime(new DateTime('now'));
        $message->setEventId($event);

        $messageForm = $this->createForm(MessageType::class, $message);
        $messageForm->handleRequest($request);

        if ($messageForm->isSubmitted() && $messageForm->isValid()) {
            /** @var UploadedFile $mediaUrlFile */
            $mediaUrlFile = $messageForm->get('mediaUrl')->getData();
            if (!empty($mediaUrlFile)) {
                $mediaUrlFileName = $fileUploader->upload($mediaUrlFile);
                $message->setMediaUrl($mediaUrlFileName);
            }

            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('wedding_diary', ['id' => $id]);
        }

        /* //// Messages Display //// */
        $messagesList = $messageRepository->findBy(
            ['eventId' => $id],
            ['messageDateTime' => 'DESC'],
            10
        );

        return $this->render('diary/diary.html.twig', [
            'message' => $message,
            'form' => $messageForm->createView(),
            'messagesList' => $messagesList,
            'event' => $event,
        ]);
    }

    /**
     * @Route("/{id}/bannerDiary", name="banner_diary", methods={"GET","POST"})
     */
    public function bannerDiary(
        Wedding $event,
        Request $request,
        EntityManagerInterface $entityManager,
        FileUploader $fileUploader,
        BannerUpdate $bannerUpdate
    ): Response {
        if ($request->request != null) {
            $bannerUpdate->update($request, $event, Wedding::class);

            $requestPicture = $request->files->get('image');

            /** @var UploadedFile $pictureFile */
            $pictureFile = $requestPicture;
            if (!empty($pictureFile)) {
                $pictureFileName = $fileUploader->upload($pictureFile);
                $event->setEventPicture($pictureFileName);
            }

            $entityManager->flush();
        }

        return $this->json([
            'code' => 200,
            'image' => $event->getEventPicture()
        ], 200);
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

        if ($form->isSubmitted() && $form->isValid() && !empty($wedding)) {
            /** @var UploadedFile $eventPicture */
            $eventPicture = $form->get('eventPicture')->getData();
            if (!empty($eventPicture)) {
                $eventPictureFileName = $fileUploader->upload($eventPicture);
                $wedding->setEventPicture($eventPictureFileName);
            }

                $manager->persist($wedding);
                $manager->flush();

            return $this->redirectToRoute('wedding_index');
        }

        return $this->render('wedding/createDiary.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
