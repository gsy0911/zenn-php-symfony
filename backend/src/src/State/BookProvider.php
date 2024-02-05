<?php

namespace App\State;

use App\Repository\BookRepository;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\State\ProviderInterface;
use Psr\Log\LoggerInterface;


readonly class BookProvider implements ProviderInterface
{
    public function __construct(
        private BookRepository $bookRepository,
        private LoggerInterface $logger,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            return $this->bookRepository->findAll();
        }
        $this->logger->info(http_build_query($uriVariables));
        $this->logger->info(http_build_query($context));
        $this->logger->info($uriVariables['id']);
        // Retrieve the state from somewhere
        return $this->bookRepository->find($uriVariables['id']);
    }
}
