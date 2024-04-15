<?php

namespace App\Infrastructure\ServiceGateway;

use App\Domain\Book\Book;
use App\Domain\Book\ServiceGateway\ServiceGatewayInterface;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class OpenLibraryGateway implements ServiceGatewayInterface
{
    public function __construct(
        private HttpClientInterface $openLibraryClient,
        private LoggerInterface $logger,
    ) {
    }

    public function getBook(string $isbn): ?Book
    {
        return $this->byIsbn($isbn);
    }

    /**
     * @return iterable<Book>
     */
    public function getBooks(string $keyword): iterable
    {
        yield from $this->byParameters($keyword);
    }

    private function byIsbn(string $isbn): ?Book
    {
        $bibKeys = "ISBN:{$isbn}";
        $response = $this->openLibraryClient->request(
            method: 'GET',
            url: "/api/books?bibkeys={$bibKeys}&format=json&jscmd=data",
        );

        $result = $response->toArray();

        if (false === isset($result[$bibKeys])) {
            return null;
        }

        $bookDetails = $result[$bibKeys];
        $identifiers = $bookDetails['identifiers'];

        if (false === isset($identifiers['isbn_10'])) {
            return null;
        }

        $title = $bookDetails['title'];

        if (isset($bookDetails['subtitle'])) {
            $title .= ' - '.$bookDetails['subtitle'];
        }

        return $this->createBook([
            'isbn10' => $identifiers['isbn_10'],
            'isbn13' => $identifiers['isbn_13'] ?? null,
            'title' => $title,
            'page' => $bookDetails['number_of_pages'] ?? null,
            'publicationDate' => $bookDetails['publish_date'] ?? null,
        ]);
    }

    /**
     * @return iterable<Book>
     */
    private function byParameters(string $parameter): iterable
    {
        $response = $this->openLibraryClient->request(
            method: 'GET',
            url: "/search.json?q={$parameter}",
        );

        $results = $response->toArray();
        foreach ($results['docs'] as $result) {
            if (false === isset($result['isbn'])) {
                continue;
            }

            $book = $this->byIsbn($result['isbn'][0]);

            if (null !== $book) {
                yield $book;
            }
        }
    }

    private function createBook(array $parameters): Book
    {
        $publicationDate = $this->handleDateFormat($parameters['publicationDate']);

        return new Book(
            isbn10: current($parameters['isbn10']),
            isbn13: $parameters['isbn13'] ? current($parameters['isbn13']) : null,
            title: $parameters['title'],
            page: $parameters['page'],
            publicationDate: $publicationDate,
        );
    }

    private function handleDateFormat(?string $date): ?DateTimeImmutable
    {
        if (null === $date) {
            return null;
        }

        $publicationDate = DateTimeImmutable::createFromFormat('F d, Y', $date);
        if (false === $publicationDate) {
            $publicationDate = DateTimeImmutable::createFromFormat('Y', $date);

            if (false === $publicationDate) {
                $publicationDate = DateTimeImmutable::createFromFormat('Y-m', substr($date, 0, 7));

                if (false === $publicationDate) {
                    $publicationDate = DateTimeImmutable::createFromFormat('F Y', $date);

                    if (false === $publicationDate) {
                        $this->logger->warning('Unknown date format', [
                            'date' => $date,
                        ]);
                    }
                }
            }
        }

        return $publicationDate;
    }
}
