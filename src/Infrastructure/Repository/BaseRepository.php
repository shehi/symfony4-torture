<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\NotSupported;

abstract class BaseRepository
{
    protected EntityManagerInterface $manager;

    protected EntityRepository $repository;

    /**
     * @throws NotSupported
     */
    public function __construct(EntityManagerInterface $manager, string $entityName)
    {
        $this->manager = $manager;
        $this->repository = $manager->getRepository($entityName);
    }
}

