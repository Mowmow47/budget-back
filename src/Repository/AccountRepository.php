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
     * Retrieve all the accounts from the database (it will only retrieve the accounts which belongs to the authenticated user in the future)
     * 
     * @param string $date
     * @return Account[] Returns an array of Account objects
     */
    public function findAccountsWithTransactions(string $date = 'Y-m-d'): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.transactions', 't')
            ->where('t.date <= :now')
            ->setParameter('now', date($date))
            ->addSelect('t')
            ->getQuery()
            ->getResult()
        ;
    }
}
