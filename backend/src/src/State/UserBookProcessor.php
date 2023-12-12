<?php

namespace App\State;

use App\Entity\User;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\State\ProcessorInterface;
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
        if ($operation instanceof DeleteOperationInterface) {
            if ($data instanceof User) {
                $book = $this->bookRepository->find($uriVariables['bookId']);
                $data->removeBook($book);
                $book->removeUser($data);
                $this->entityManager->persist($data);
                $this->entityManager->persist($book);
                $this->entityManager->flush();
            }
        } else {
            if ($data instanceof User) {
                $book = $this->bookRepository->find($uriVariables['bookId']);
                $data->addBooks($book);
                $book->addUser($data);
                $this->entityManager->persist($data);
                $this->entityManager->persist($book);
                $this->entityManager->flush();
            }
        }
        // Handle the state
        $this->logger->info("occurred!");
    }
}
