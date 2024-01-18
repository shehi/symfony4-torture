<?php

namespace Tests\Functional;

use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    public function test__index(): void
    {
        static::$client->request('GET', '/api/');
        static::assertResponseIsSuccessful();
    }
}
