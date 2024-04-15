<?php

declare(strict_types=1);

namespace App\Infrastructure\Command;

use App\Infrastructure\Messaging\Message\SaveAsyncBookMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

#[AsCommand(
    name: 'app:messenger:producer',
    description: 'Send record to messenger transport.',
)]
class KafkaProducerCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command allows you to send message to messenger transport.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->messageBus->dispatch(
                new SaveAsyncBookMessage(
                    books: [],
                ),
            );
        } catch (Throwable $e) {
            $output->writeln(sprintf(
                '<error>Failed to send message with error message : %s</error>',
                $e->getMessage(),
            ));

            return Command::FAILURE;
        }

        $output->writeln('<info>Message message sent</info>');

        return Command::SUCCESS;
    }
}
