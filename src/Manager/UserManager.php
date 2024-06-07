<?php

namespace App\Manager;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserManager extends BaseManager
{
    public function __construct(EntityManagerInterface $em, UserRepository $repository)
    {
        parent::__construct($em, $repository);
        $this->repository = $repository;
    }

    public function create(): User
    {
        return new User();
    }

    public function byToken(string $token): ?User
    {
        return $this->repository->byToken($token);
    }

    public function byEmail(string $email): ?User
    {
        return $this->repository->byEmail($email);
    }
}
