<?php

namespace App\Serializer;

use ApiPlatform\Api\IriConverterInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Psr\Log\LoggerInterface;
use App\Entity\User;
use App\Entity\Book;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;

class BookDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function __construct(
        private readonly IriConverterInterface $iriConverter,
        private readonly LoggerInterface $logger,
    )
    {
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        // TODO $dataからnormalizerが動くようにする
        //  現状は、$data["books"]を削除してUserオブジェクトを作成して、複数のBookオブジェクトをあとから追加する形にしている
        //  それを解決したい
        $books = new ArrayCollection();
        foreach($data['books'] as $index => $bookData) {
            $iri = $this->iriConverter->getIriFromResource(resource: Book::class, context: ['uri_variables' => ['id' => $bookData]]);
            $this->logger->info("iri := " . $iri);
            $book = $this->denormalizer->denormalize($iri, $type, $format, $context + [__CLASS__ => true]);
            // $books->add($iri);
            $books->add($book);
        }
        unset($data["books"]);
        // $data["books"] = $books->toArray();
        $user = $this->denormalizer->denormalize($data, $type, $format, $context + [__CLASS__ => true]);
        $user->setBooks($books);
        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        return \in_array($format, ['json', 'jsonld'], true) && is_a($type, User::class, true) && !empty($data['books']) && !isset($context[__CLASS__]);
    }
}
