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

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $this->logger->info("process started!");
        if ($operation instanceof DeleteOperationInterface) {
            if ($data instanceof User) {
                $book = $this->bookRepository->find($uriVariables['bookId']);
                $data->removeBook($book);
                $book->removeUser($data);
                $this->entityManager->persist($book);
                $this->entityManager->persist($data);
//                $this->entityManager->remove($book);
//                $this->entityManager->remove($data);
                $this->entityManager->flush();
            }
        } else {
            $this->logger->info("data processing");
            if ($data instanceof User) {
                foreach($data->getBooks() as $index => $bookData) {
                    $bookData->addUser($data);
                    $this->entityManager->persist($bookData);
                }
                $this->entityManager->persist($data);
                $this->entityManager->flush();
            }
        }
        // Handle the state
        $this->logger->info("process finished!");
        return $data;
    }
}
