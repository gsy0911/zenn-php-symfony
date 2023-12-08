<?php

namespace App\State;

use App\Entity\Book;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

readonly class BookProcessor implements ProcessorInterface
{
    public function __construct(
        private AuthorRepository $authorRepository,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
    )
    {

    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if ($data instanceof Book) {
            $author = $this->authorRepository->find($uriVariables['authorId']);
            $data->setAuthor($author);
            $this->logger->info($author->getName());
            $this->logger->info($author->getId());
            $this->logger->info($data->getTitle());
            $this->entityManager->persist($data);
            $this->entityManager->flush();
        }
        // Handle the state
    }
}
