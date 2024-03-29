<?php

namespace App\State;

use App\Entity\User;
use App\Entity\Book;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

readonly class UserBookProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $this->logger->info("/users/{id}/books/*");
        if ($operation instanceof DeleteOperationInterface) {

            if ($data instanceof User) {
                $book = $this->entityManager->getRepository(Book::class)->find($uriVariables['bookId']);
                $book->removeUser($data);
                $this->entityManager->flush();
                return $data;
            }
        } else {
            if ($data instanceof User) {
                foreach($data->getBooks() as $index => $bookData) {
                    $bookData->addUser($data);
                }
                $this->entityManager->flush();
            }
        }
        // Handle the state
        return $data;
    }
}
