<?php

namespace App\Repository;

use App\Entity\Abonnement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Abonnement>
 */
class AbonnementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abonnement::class);
    }

    /**
     * Find all Abonnements ordered by ID
     *
     * @return Abonnement[] Returns an array of Abonnement objects
     */
    public function findAllOrderedById(): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find Abonnements by salle ID (SalleDeSport)
     *
     * @param int $salleId The ID of the SalleDeSport
     * @return Abonnement[] Returns an array of Abonnement objects
     */
    public function findBySalleId(int $salleId): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.salle = :salleId')
            ->setParameter('salleId', $salleId)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find Abonnements by Client ID
     *
     * @param int $clientId The ID of the client
     * @return Abonnement[] Returns an array of Abonnement objects
     */
    public function findByClientId(int $clientId): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.client = :clientId')
            ->setParameter('clientId', $clientId)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find Abonnements within a specific date range
     *
     * @param \DateTime $startDate The start date of the range
     * @param \DateTime $endDate The end date of the range
     * @return Abonnement[] Returns an array of Abonnement objects
     */
    public function findByDateRange(\DateTime $startDate, \DateTime $endDate): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.dateDebut >= :startDate')
            ->andWhere('a.dateFin <= :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find a single Abonnement by ID
     *
     * @param int $id The ID of the Abonnement
     * @return Abonnement|null Returns a single Abonnement or null
     */
    public function findOneById(int $id): ?Abonnement
    {
        return $this->find($id);
    }

    /**
     * Find Abonnements by a field, such as 'nom' or 'prix'
     *
     * @param string $field The field to search on (e.g., 'nom', 'prix')
     * @param mixed $value The value to search for
     * @return Abonnement[] Returns an array of Abonnement objects
     */
    public function findByField(string $field, $value): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere("a.{$field} = :value")
            ->setParameter('value', $value)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find all Abonnements that have not been assigned to a client (null client)
     *
     * @return Abonnement[] Returns an array of Abonnement objects
     */
    public function findUnassignedAbonnements(): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.client IS NULL')
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find the latest Abonnement (ordered by dateFin descending)
     *
     * @return Abonnement|null Returns the most recent Abonnement or null
     */
    public function findLatestAbonnement(): ?Abonnement
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.dateFin', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
