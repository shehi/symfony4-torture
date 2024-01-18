<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Invitation;

interface InvitationRepository
{
    public function find($id): ?Invitation;

    /**
     * @return Invitation[]
     */
    public function findAll(): array;

    /**
     * @param array $criteria
     * @param null|array $orderBy
     * @param null $limit
     * @param null $offset
     *
     * @return Invitation[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array;

    public function persist(Invitation $entity, array $context = []): void;

    public function delete(Invitation $entity): void;
}
