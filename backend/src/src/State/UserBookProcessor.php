<?php

namespace App\State;

use App\Entity\User;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\UserRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

readonly class UserBookProcessor implements ProcessorInterface
{
    public function __construct(
        private BookRepository $bookRepository,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
    )
    {

    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        $this->logger->info("lock!");
        if ($data instanceof User) {
            $book = $this->bookRepository->find($uriVariables['bookId']);
            $data->addBook($book);
            $book->addUser($data);
            $this->logger->info("fire!");
            $this->logger->info($book->getTitle());
            $this->logger->info($data->getName());
            $this->logger->info($data->getBook()[0]->getTitle());
            $this->entityManager->persist($data);
            $this->entityManager->persist($book);
            $this->entityManager->flush();
        }
        // Handle the state
        $this->logger->info("occurred!");
    }
}
