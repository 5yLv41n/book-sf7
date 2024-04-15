<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Infrastructure\DTO\CreateBookDTO;
use App\Infrastructure\Messaging\Message\SaveBookMessage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final readonly class CreateBookController
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    #[Route(path: '/book', name: 'book_save', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload] CreateBookDTO $dto = new CreateBookDTO(
            isbn10: null,
            isbn13: null,
            title: null,
            page: null,
            publicationDate: null,
        ),
    ): Response {
        $this->messageBus->dispatch(new SaveBookMessage(dto: $dto));

        return new Response(status: Response::HTTP_CREATED);
    }
}
