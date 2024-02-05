<?php

namespace App\State;

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
        $this->logger->info(http_build_query($uriVariables));
        $this->logger->info(http_build_query($context));
        $this->logger->info("hello!!!");
        // Retrieve the state from somewhere
        return $this->userRepository->findOne(id: $uriVariables["id"]);
    }
}
