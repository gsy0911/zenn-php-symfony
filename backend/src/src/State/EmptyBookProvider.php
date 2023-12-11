<?php

namespace App\State;

use App\Entity\Book;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;


readonly class EmptyBookProvider implements ProviderInterface
{
    public function __construct(
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        // Retrieve the state from somewhere
        return new Book();
    }
}
