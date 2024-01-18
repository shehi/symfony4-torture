<?php

namespace App\Domain\Repository;

use App\Domain\Entity\User;

interface UserRepository
{
    public function find($id): ?User;

    /**
     * @return User[]
     */
    public function findAll(): array;

    /**
     * @param array $criteria
     * @param null|array $orderBy
     * @param null $limit
     * @param null $offset
     *
     * @return User[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array;

    public function persist(User $entity, array $context = []): void;

    public function delete(User $entity): void;
}
