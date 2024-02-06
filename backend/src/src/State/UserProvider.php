<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Psr\Log\LoggerInterface;


readonly class UserProvider implements ProviderInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private LoggerInterface $logger,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            return $this->userRepository->findAll();
        }

        $this->logger->info(http_build_query($uriVariables));
        $this->logger->info(http_build_query($context));
        $this->logger->info("hello!!!");
        // Retrieve the state from somewhere
        return $this->userRepository->findOneOrNull(id: $uriVariables["id"]);
    }
}
