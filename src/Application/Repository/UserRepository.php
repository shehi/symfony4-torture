<?php

declare(strict_types=1);

namespace App\Application\Repository;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository as UserRepositoryInterface;
use App\Infrastructure\Repository\BaseRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, User::class);
    }

    public function find($id): ?User
    {
        return $this->repository->find($id);
    }

    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function persist(User $entity, array $context = []): void
    {
        $this->manager->persist($entity);
        $this->manager->flush();
    }

    public function delete(User $entity): void
    {
        $this->manager->remove($entity);
        $this->manager->flush();
    }
}
