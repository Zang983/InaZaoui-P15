<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @implements PasswordUpgraderInterface<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findAdmin(): ?User
    {
        $users = $this->createQueryBuilder('u')
            ->getQuery()
            ->getResult();

        if(!is_array($users)) {
            return null;
        }
        /** @var User[] $users */
        $filteredUsers = array_filter($users, function ($user) {
            return in_array("ROLE_ADMIN", $user->getRoles());
        });

        return $filteredUsers ? reset($filteredUsers) : null;
    }


    /**
     * @return User[]
     */
    public function findAllGuestsWithMedia(): array
    {
        $users = $this->createQueryBuilder('u')
            ->leftJoin('u.medias', 'm')
            ->addSelect('m')
            ->getQuery()
            ->getResult();


        if (!is_array($users)) {
            return [];
        }
        /** @var User[] $users */
        $filteredUsers = array_filter($users, function ($user) {
            return !in_array("ROLE_ADMIN", $user->getRoles());
        });

        return $filteredUsers ? $filteredUsers : [];
    }

    public function findOneGuestWithMedia(int $id): ?User
    {
        $user = $this->createQueryBuilder('u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->leftJoin('u.medias', 'm')
            ->addSelect('m')
            ->getQuery()
            ->getOneOrNullResult();

        return $user instanceof User ? $user : null;
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
