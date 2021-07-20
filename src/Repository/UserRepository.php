<?php

namespace App\Repository;

use App\Entity\User;
use DateTime;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use League\OAuth2\Client\Provider\GoogleResourceOwner;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use PhpParser\Node\Expr\Cast\String_;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private SubscriptionRepository $subscripRepository;
    public function __construct(ManagerRegistry $registry, SubscriptionRepository $subscripRepository)
    {
        parent::__construct($registry, User::class);
        $this->subscripRepository = $subscripRepository;
    }
    public function findOrCreateFromGoogleOauth(GoogleUser $owner): User
    {
        $user = $this->createQueryBuilder('u')
            ->where('u.googleId = :googleId')
            ->setParameters([
                'googleId' => $owner->getId()
            ])
            ->getQuery()
            ->getOneOrNullResult();
        if ($user) {
            return $user;
        }
        $user = (new user())
            ->setRoles(['ROLE_USER'])
            ->setGoogleId($owner->getId())
            ->setEmail($owner->getEmail() ?? '')
            ->setFirstName($owner->getFirstName() ?? '')
            ->setLastname($owner->getName())
            ->setBirthDate(new DateTime('1900-01-01'))
            ->setPassword('int');
            $freeSubscription = $this->subscripRepository->findOneBy(['name' => 'GRATUIT']);
        if ($freeSubscription !== null) {
            $freeSubscription->addUser($user);
        }
        $user->setSubscription($freeSubscription);
        $entityManager = $this->getEntityManager();
        $entityManager->persist($user);
        $entityManager->flush();

            return $user;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
