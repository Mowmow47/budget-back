<?php

namespace App\Repository;

use App\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Account>
 *
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function add(Account $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Account $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Returns the Account object for the given id with all the related transactions.
     * 
     * @param int $id
     * @return Account Returns an Account object or null
     */
    public function findAccountsWithTransactions(int $id): Account
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.transactions', 't')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->addSelect('t')
            ->orderBy('t.date', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
