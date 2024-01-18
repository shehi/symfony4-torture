<?php

declare(strict_types=1);

namespace Tests;

use Exception;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpKernel\KernelInterface;

class TestCase extends WebTestCase
{
    protected static KernelBrowser $client;

    /**
     * @throws Exception
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::$client = static::createClient();
//        static::$client->catchExceptions(false);
        static::loadFixtures(static::$kernel);
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        static::ensureKernelShutdown();
    }

    /**
     * We override tearDown() in order not to lose $client and $container.
     */
    protected function tearDown(): void
    {
        // Do nothing.
    }

    /**
     * @throws Exception
     */
    protected static function loadFixtures(KernelInterface $kernel): void
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);

        $commands = [
            new ArrayInput(['command' => 'doctrine:schema:drop', '--force' => true, '--quiet' => true]),
            new ArrayInput(['command' => 'doctrine:schema:create', '--no-interaction' => true, '--quiet' => true]),
            new ArrayInput(['command' => 'doctrine:fixtures:load', '--no-interaction' => true, '--quiet' => true]),
        ];

        foreach ($commands as $command) {
            $application->run($command);
        }
    }

    protected static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        return parent::createClient($options, array_merge($server, ['base_uri' => 'http://localhost']));
    }
}
