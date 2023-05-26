<?php

namespace App\Tests\Service;

use App\Services\OmdbApi;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OmdbApiTest extends KernelTestCase
{
    public function testOmdbApi(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());

        $omdbApi = static::getContainer()->get(OmdbApi::class);

        $infosOdmb = $omdbApi->fetch("Stranger Things");
        $posterExpected = "https://m.media-amazon.com/images/M/MV5BMDZkYmVhNjMtNWU4MC00MDQxLWE3MjYtZGMzZWI1ZjhlOWJmXkEyXkFqcGdeQXVyMTkxNjUyNQ@@._V1_SX300.jpg";

        $this->assertEquals($posterExpected, $infosOdmb->getPoster());

        $infosOdmb = $omdbApi->fetch("aaaaaaaaaaaa");
        $expected = "False";
        $this->assertEquals($expected, $infosOdmb->getResponse());

    }
}
