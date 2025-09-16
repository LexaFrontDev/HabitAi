<?php

namespace App\Infrastructure\Command;

use App\Aplication\UseCase\ResourceUseCase\CommandResourceUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:resources:sync',
    description: 'Синхронизировать ресурсы из базы в хранилище'
)]
class SyncResourcesCommand extends Command
{
    public function __construct(
        private CommandResourceUseCase $resourceUseCase,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->resourceUseCase->saveResources();
            $output->writeln('<info>Ресурсы успешно синхронизированы.</info>');

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $output->writeln('<error>Ошибка: '.$e->getMessage().'</error>');

            return Command::FAILURE;
        }
    }
}
