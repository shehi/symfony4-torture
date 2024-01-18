<?php

namespace Tests\Functional;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class InvitationsControllerTest extends TestCase
{
    public function test__store__failure__sender_or_recipient_not_set(): void
    {
        static::$client->request('POST', '/api/invitations/', [
            'sender_id' => 1,
        ]);
        static::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function test__store__failure__recipient_not_found(): void
    {
        static::$client->request('POST', '/api/invitations/', [
            'sender_id' => 1,
            'recipient_id' => 200,
        ]);
        static::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function test__store__failure__sender_not_found(): void
    {
        static::$client->request('POST', '/api/invitations/', [
            'sender_id' => 100,
            'recipient_id' => 2,
        ]);
        static::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws \JsonException
     */
    public function test__store__success(): void
    {
        static::$client->request('POST', '/api/invitations/', [
            'sender_id' => 1,
            'recipient_id' => 2,
        ]);
        static::assertResponseIsSuccessful(Response::HTTP_NOT_FOUND);

        $response = static::$client->getResponse();
        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        static::assertEquals('application/json', $response->headers->get('Content-Type'));
        static::assertArrayHasKey('id', $data);
        static::assertArrayHasKey('accepted', $data);
        static::assertArrayHasKey('sender', $data);
        static::assertIsArray($data['sender']);
        static::assertEquals(1, $data['sender']['id']);
        static::assertEquals('sender@example.com', $data['sender']['email']);
        static::assertArrayHasKey('recipient', $data);
        static::assertIsArray($data['recipient']);
        static::assertEquals(2, $data['recipient']['id']);
        static::assertEquals('recipient@example.com', $data['recipient']['email']);
    }

    public function test__show__failure__404(): void
    {
        static::$client->request('GET', '/api/invitations/100');
        static::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function test__show__success(): void
    {
        static::$client->request('GET', '/api/invitations/1');
        static::assertResponseIsSuccessful();
        $response = static::$client->getResponse();
        static::assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    /**
     * @throws \JsonException
     */
    public function test__put__success__accept(): void
    {
        static::$client->request('PUT', '/api/invitations/1', [
            'is_accepted' => 1,
        ]);
        static::assertResponseIsSuccessful();

        $response = static::$client->getResponse();
        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        static::assertEquals('application/json', $response->headers->get('Content-Type'));
        static::assertArrayHasKey('id', $data);
        static::assertArrayHasKey('accepted', $data);
        static::assertTrue($data['accepted']);
    }

    /**
     * @throws \JsonException
     */
    public function test__put__success__decline(): void
    {
        static::$client->request('PUT', '/api/invitations/1', [
            'is_accepted' => 0,
        ]);
        static::assertResponseIsSuccessful();

        $response = static::$client->getResponse();
        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        static::assertEquals('application/json', $response->headers->get('Content-Type'));
        static::assertArrayHasKey('id', $data);
        static::assertArrayHasKey('accepted', $data);
        static::assertFalse($data['accepted']);
    }

    public function test__put__failure__missing_parameter(): void
    {
        static::$client->request('PUT', '/api/invitations/1');
        static::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function test__delete__failure__missing_object(): void
    {
        static::$client->request('DELETE', '/api/invitations/100');
        static::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function test__delete__success(): void
    {
        static::$client->request('DELETE', '/api/invitations/1');
        static::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
