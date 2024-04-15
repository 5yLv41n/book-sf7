<?php

declare(strict_types=1);

namespace App\Infrastructure\Command;

use App\Infrastructure\Messaging\Message\FindBooksMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'app:find-books')]
final class FindBooksCommand extends Command
{
    use HandleTrait;

    public function __construct(
        private MessageBusInterface $queryBus,
    ) {
        $this->messageBus = $queryBus;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $books = $this->handle(new FindBooksMessage());

        foreach($books as $book) {
            dump($book);
        }

        return Command::SUCCESS;
    }
}
