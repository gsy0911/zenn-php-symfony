<?php

namespace App\State;

use App\Entity\User;
use App\Service\UserIdService;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Psr\Log\LoggerInterface;


readonly class UserProvider implements ProviderInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private UserIdService $userIdService,
        private LoggerInterface $logger,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $this->logger->info(http_build_query($uriVariables));
        $this->logger->info(http_build_query($context));
        $this->logger->info("hello!!!");
//        $this->logger->info($this->userIdService->getUserId());
//        $qb = $this->userRepository->createQueryBuilder('user')
//            ->leftJoin('user.books', 'b')
//            ->andWhere('user.id = :userId')
//            ->setParameter('userId', $this->userIdService->getUserId())
//            ->getQuery()
//        ;
        // Retrieve the state from somewhere
//        return $qb->getResult();
        return $this->userRepository->find($this->userIdService->getUserId());
    }
}
