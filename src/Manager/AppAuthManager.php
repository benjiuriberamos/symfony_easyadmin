<?php

namespace App\Manager;

use App\Entity\AppAuth;
use App\Repository\AppAuthRepository;
use Doctrine\ORM\EntityManagerInterface;

class AppAuthManager extends BaseManager
{
    public function __construct(EntityManagerInterface $em, AppAuthRepository $repository)
    {
        parent::__construct($em, $repository);
    }

    public function create(): AppAuth
    {
        return new AppAuth();
    }
}
