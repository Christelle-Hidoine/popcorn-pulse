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
        
        // TODO tester le service OmdbApi
        // 1. on doit récupérer notre service
        // on est pas dans symfony, on est dans le framework de test
        // on a donc pas l'injection de dépendance
        // on va donc utiliser les raccourcies fournit par symfony à PHPUnit (framework de test)
        // * on demande au conteneur de services de nous fournir notre service
        $omdbApi = static::getContainer()->get(OmdbApi::class);

        $infosOdmb = $omdbApi->fetch("Stranger Things");
        $posterExpected = "https://m.media-amazon.com/images/M/MV5BMDZkYmVhNjMtNWU4MC00MDQxLWE3MjYtZGMzZWI1ZjhlOWJmXkEyXkFqcGdeQXVyMTkxNjUyNQ@@._V1_SX300.jpg";

        $this->assertEquals($posterExpected, $infosOdmb->getPoster());

        $infosOdmb = $omdbApi->fetch("aaaaaaaaaaaa");
        // dd($infosOdmb);
        $expected = "False";
        $this->assertEquals($expected, $infosOdmb->getResponse());

    }
}
