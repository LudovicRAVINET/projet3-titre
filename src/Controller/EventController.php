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
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\EventRepository;
use App\Repository\MessageRepository;
use App\Service\BannerUpdate;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

        if ($this->getUser() !== null) {

            /** @var \App\Entity\User $user */
            $user = $this->getUser();

            dd($request);


        /** @var \App\Entity\Type $eventType */
            $eventType = htmlentities(trim($request->get('event_type')));
            $eventName = htmlentities(trim($request->get('event_name')));
            $eventDate = htmlentities(trim($request->get('event_date')));
            $eventTime = htmlentities(trim($request->get('event_time')));
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
                    ->setTime(new DateTime($eventTime))
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
        }

        return $this->redirectToRoute('home_index');
    }

    /**
     * @Route("/event/{id}/diary", name="event_diary", methods={"GET","POST"})
     */
    public function diary(
        int $id,
        MessageRepository $messageRepository,
        EventRepository $eventRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        FileUploader $fileUploader
    ): Response {

        /* //// Message Form //// */
        /** @var \App\Entity\Event $event */
        $event = $eventRepository->find($id);
        $message = new Message();
        $message->setDateTime(new DateTime('now'));
        $message->setEvent($event);

        $messageForm = $this->createForm(MessageType::class, $message);
        $messageForm->handleRequest($request);

        if ($messageForm->isSubmitted() && $messageForm->isValid()) {
            /** @var UploadedFile $mediaUrlFile */
            $mediaUrlFile = $messageForm->get('url')->getData();
            if (!empty($mediaUrlFile)) {
                $mediaUrlFileName = $fileUploader->upload($mediaUrlFile);
                $message->setUrl($mediaUrlFileName);
            }

            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('event_diary', ['id' => $id]);
        }

        /* //// Messages Display //// */
        $messagesList = $messageRepository->findBy(
            ['event' => $id],
            ['datetime' => 'DESC'],
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
     * @Route("/event/{id}/bannerDiary", name="banner_diary", methods={"GET","POST"})
     */
    public function bannerDiary(
        Event $event,
        Request $request,
        EntityManagerInterface $entityManager,
        FileUploader $fileUploader,
        BannerUpdate $bannerUpdate
    ): Response {
        if ($request->request != null) {
            $bannerUpdate->update($request, $event, Event::class);

            $requestPicture = $request->files->get('image');

            /** @var UploadedFile $pictureFile */
            $pictureFile = $requestPicture;
            if (!empty($pictureFile)) {
                $pictureFileName = $fileUploader->upload($pictureFile);
                $event->setImage($pictureFileName);
            }

            $entityManager->flush();
        }

        return $this->json([
            'code' => 200,
            'image' => $event->getImage()
        ], 200);
    }
}
