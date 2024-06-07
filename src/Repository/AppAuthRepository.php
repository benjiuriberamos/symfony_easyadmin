<?php

namespace App\Repository;

use App\Entity\AppAuth;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AppAuth|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppAuth|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppAuth[]    findAll()
 * @method AppAuth[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppAuthRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppAuth::class);
    }
}
