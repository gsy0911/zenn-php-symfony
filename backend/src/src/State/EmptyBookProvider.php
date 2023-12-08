<?php

namespace App\State;

use App\Entity\Book;
use App\Repository\BookRepository;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Psr\Log\LoggerInterface;


readonly class EmptyBookProvider implements ProviderInterface
{
    public function __construct(
        private BookRepository $bookRepository,
        private LoggerInterface $logger,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        // Retrieve the state from somewhere
        return new Book();
    }
}
