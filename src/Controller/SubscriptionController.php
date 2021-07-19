<?php

namespace App\Controller;

use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/subscription", name="subscription_")
*/
class SubscriptionController extends AbstractController
{
    /**
     * @Route("/choice/{id}", name="choice")
     */
    public function choice(
        int $id,
        EntityManagerInterface $manager,
        SubscriptionRepository $subscripRepository
    ): Response {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $subscriptionChoice = $subscripRepository->find($id);
        if ($subscriptionChoice !== null) {
            $subscriptionChoice->addUser($user);
        }

        $manager->flush();

        return $this->redirectToRoute('profile_index', ['id' => $user->getId()]);
    }
}
