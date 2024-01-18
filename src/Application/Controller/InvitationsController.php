<?php

declare(strict_types=1);

namespace App\Application\Controller;

use App\Domain\Entity\Invitation;
use App\Domain\Repository\InvitationRepository;
use App\Domain\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/invitations")
 */
class InvitationsController extends AbstractController
{
    private InvitationRepository $invitationRepository;

    private SerializerInterface $serializer;

    public function __construct(InvitationRepository $invitationRepository, SerializerInterface $serializer)
    {
        $this->invitationRepository = $invitationRepository;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/", methods={"POST"})
     */
    public function store(UserRepository $userRepository, Request $request): JsonResponse {
        if (!$request->request->has('sender_id') || !$request->request->has('recipient_id')) {
            throw new BadRequestHttpException('"sender_id" and "recipient_id" are required.');
        }

        $invitation = new Invitation();
        if (!$sender = $userRepository->find($request->request->get('sender_id'))) {
            throw $this->createNotFoundException('Sender not found.');
        }
        if (!$recipient = $userRepository->find($request->request->get('recipient_id'))) {
            throw $this->createNotFoundException('Recipient not found.');
        }
        $invitation->setSender($sender);
        $invitation->setRecipient($recipient);
        $this->invitationRepository->persist($invitation);

        $data = $this->serializer->normalize($invitation, null, [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn ($object, $format, $context) => sprintf('%s::%d', get_class($object), $object->getId()),
        ]);

        return $this->json($data, JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function show(int $id): JsonResponse
    {
        if (!($invitation = $this->invitationRepository->find($id))) {
            throw $this->createNotFoundException('Invitation not found.');
        }

        $data = $this->serializer->normalize($invitation, null, [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn ($object, $format, $context) => sprintf('%s::%d', get_class($object), $object->getId()),
        ]);

        return $this->json($data);
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     */
    public function edit(int $id, Request $request): JsonResponse
    {
        if (!($invitation = $this->invitationRepository->find($id))) {
            throw $this->createNotFoundException('Invitation not found.');
        }

        if (!$request->request->has('is_accepted')) {
            throw new BadRequestHttpException('"is_accepted" is required.');
        }

        $invitation->setIsAccepted((bool)$request->request->get('is_accepted'));
        $this->invitationRepository->persist($invitation);

        $data = $this->serializer->normalize($invitation, null, [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn ($object, $format, $context) => sprintf('%s::%d', get_class($object), $object->getId()),
        ]);

        return $this->json($data);
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     */
    public function destroy(int $id): Response
    {
        if (!($invitation = $this->invitationRepository->find($id))) {
            throw $this->createNotFoundException('Invitation not found.');
        }

        $this->invitationRepository->delete($invitation);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
