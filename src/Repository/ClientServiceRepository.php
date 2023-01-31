<?php

namespace App\Repository;

use App\Entity\ClientService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClientService>
 *
 * @method ClientService|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientService|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientService[]    findAll()
 * @method ClientService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientService::class);
    }

    /**
     * @param ClientService $entity
     * @param bool $flush
     * @return void
     */
    public function save(ClientService $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param ClientService $entity
     * @param bool $flush
     * @return void
     */
    public function remove(ClientService $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ClientService[] Returns an array of ClientService objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ClientService
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
