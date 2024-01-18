<?php

declare(strict_types=1);

namespace App\Application\DataFixtures;

use App\Domain\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const SENDER_REFERENCE = 'sender';
    public const RECIPIENT_REFERENCE = 'recipient';

    public function load(ObjectManager $manager): void
    {
        $this->stubUser($manager, self::SENDER_REFERENCE);
        $this->stubUser($manager, self::RECIPIENT_REFERENCE);
    }

    private function stubUser(ObjectManager $manager, string $reference): User
    {
        $user = new User();
        $user->setEmail(sprintf('%s@example.com', $reference));

        $manager->persist($user);
        $manager->flush();

        $this->addReference($reference, $user);

        return $user;
    }
}
