<?php

namespace App\State;

use App\Entity\User;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

readonly class UserProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): User
    {
        if (!($operation instanceof DeleteOperationInterface)) {
            $this->logger->info("data processing");
            $this->logger->info(gettype($data));
            $this->logger->info(json_encode($data));
            if ($data instanceof User) {
                $this->logger->info("user id is ". $data->getId());
                $this->entityManager->flush();
            }
        }
        // Update user
        return $data;
    }
}
