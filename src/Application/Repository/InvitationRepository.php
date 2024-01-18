<?php

declare(strict_types=1);

namespace App\Application\Repository;

use App\Domain\Entity\Invitation;
use App\Domain\Repository\InvitationRepository as InvitationRepositoryInterface;
use App\Infrastructure\Repository\BaseRepository;
use Doctrine\ORM\EntityManagerInterface;

class InvitationRepository extends BaseRepository implements InvitationRepositoryInterface
{
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, Invitation::class);
    }

    public function find($id): ?Invitation
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

    public function persist(Invitation $entity, array $context = []): void
    {
        $this->manager->persist($entity);
        $this->manager->flush();
    }

    public function delete(Invitation $entity): void
    {
        $this->manager->remove($entity);
        $this->manager->flush();
    }
}
