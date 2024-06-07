<?php

namespace App\Manager;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

abstract class BaseManager
{
    protected EntityManagerInterface $em;
    protected ServiceEntityRepository $repository;

    /**
     * @param EntityManagerInterface $em
     * @param ServiceEntityRepository $repository
     */
    public function __construct(EntityManagerInterface $em, ServiceEntityRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->repository->findOneBy($criteria, $orderBy);
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function create()
    {
        return null;
    }

    public function save($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
        return $entity;
    }

    public function update($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
        return $entity;
    }

    public function delete($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
        return true;
    }
}
