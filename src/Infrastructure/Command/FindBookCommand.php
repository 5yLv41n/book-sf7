<?php

declare(strict_types=1);

namespace App\Infrastructure\Command;

use App\Infrastructure\Messaging\Message\FindExternalBookMessage;
use App\Infrastructure\Messaging\Message\SaveAsyncBookMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'app:find-book')]
final class FindBookCommand extends Command
{
    use HandleTrait;

    public function __construct(
        private MessageBusInterface $queryBus,
        private MessageBusInterface $commandBus,
        private ClockInterface $clock,
        private LoggerInterface $logger,
    ) {
        $this->messageBus = $queryBus;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(name: 'keyword', mode: InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $books = $this->handle(new FindExternalBookMessage(keyword: $input->getArgument('keyword')));
        $books = iterator_to_array($books);

        $this->commandBus->dispatch(new SaveAsyncBookMessage(
            books: $books,
        ));

        return Command::SUCCESS;
    }
}
