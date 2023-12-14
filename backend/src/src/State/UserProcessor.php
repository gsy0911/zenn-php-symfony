<?php

namespace App\State;

use App\Entity\User;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

readonly class UserProcessor implements ProcessorInterface
{
    public function __construct(
        private BookRepository $bookRepository,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): User
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
            $this->logger->info("data processing");
            $this->logger->info(gettype($data));
            $this->logger->info(json_encode($data));
            if ($data instanceof User) {
                $this->logger->info("user is ...");
                $this->logger->info($data->getName());
//                foreach($data->getBooks() as $index => $bookData) {
//                    $bookData->addUser($data);
//                    $this->logger->info($index);
//                    $this->logger->info($bookData->getTitle());
//                    $this->entityManager->persist($bookData);
//                }
                $this->entityManager->persist($data);
                $this->entityManager->flush();
            }
        }
        // Handle the state
        $this->logger->info("occurred!");
        return $data;
    }
}
