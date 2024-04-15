<?php

declare(strict_types=1);

namespace App\Infrastructure\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class FindBookControllerFunctionalTest extends WebTestCase
{
    public function testGetBookByIsbn(): void
    {
        $client = self::createClient();

        $client->request(method: 'GET', uri: '/book?isbn=2100801163');
        dd($client->getResponse()->getContent());

        $this->assertResponseIsSuccessful();
    }
}
