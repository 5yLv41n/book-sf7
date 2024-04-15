<?php

namespace App\Infrastructure\Repository;

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

final class FileSystemRepository
{
    public function __construct(
        private FileLocatorInterface $fileLocator,
    ) {
    }

    public function loadBooksSample(): iterable
    {
        $booksFile = $this->fileLocator->locate(name: 'books.json', currentPath: 'resources');
        $books = file_get_contents($booksFile);

        $serializer = new Serializer(
            [
                new DateTimeNormalizer(['datetime_format' => 'd/m/Y']),
                new GetSetMethodNormalizer(),
                new ArrayDenormalizer(),
                new ObjectNormalizer(),
            ],
            [new JsonEncoder()],
        );

        return $serializer->deserialize($books, 'App\Domain\Book\Book[]', 'json');
    }
}
