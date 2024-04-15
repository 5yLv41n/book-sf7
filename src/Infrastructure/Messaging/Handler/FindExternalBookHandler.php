<?php

declare(strict_types=1);

namespace App\Infrastructure\Messaging\Handler;

use App\Domain\Book\Book;
use App\Domain\Book\ServiceGateway\ServiceGatewayInterface;
use App\Infrastructure\Messaging\Message\FindExternalBookMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class FindExternalBookHandler
{
    public function __construct(
        public ServiceGatewayInterface $serviceGateway,
    ) {
    }

    /**
     * @return iterable<Book>
     */
    public function __invoke(FindExternalBookMessage $message): iterable
    {
        yield from $this->serviceGateway->getBooks($message->keyword);
    }
}
