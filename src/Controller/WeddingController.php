<?php

namespace App\Controller;

use App\Entity\Wedding;
use App\Form\WeddingType;
use App\Repository\WeddingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/{id}/diary", name="diary")
     */
    public function diary(int $id): Response
    {
        return $this->render('wedding/diary.html.twig');
    }

    /**
     * @Route("/{id}/createDiary", name="create_diary")
     */
    public function createDiary(
        int $id,
        Request $request,
        WeddingRepository $weddingRepository,
        EntityManagerInterface $manager
    ): Response {
        $wedding = $weddingRepository->find($id);
        $form = $this->createForm(WeddingType::class, $wedding);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($wedding)) {
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
