<?php

namespace App\Tests\Back;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserAccessTest extends WebTestCase
{
    /**
     * test url
     *
     * @dataProvider getUrls
     * 
     * @param string $url
     */
    public function testBack($url, $email, $codeStatus): void
    {
        $client = self::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail($email);
        $client->loginUser($testUser);

        $client->request('GET', $url);

        $this->assertResponseStatusCodeSame($codeStatus);
    }

    /**
     * fournit TOUS les paramètres pour une function
     * 
     * @return array
     */
    public function getUrls()
    {
        yield ['/back/main', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/movie', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/casting', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/user', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/season', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/person', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/movie', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['back/movie/new', 'manager@manager.com', Response::HTTP_FORBIDDEN];
        yield ['back/movie/new', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/casting/new', 'manager@manager.com', Response::HTTP_FORBIDDEN];
        yield ['back/user/new', 'manager@manager.com', Response::HTTP_FORBIDDEN];
        yield ['back/person/new', 'manager@manager.com', Response::HTTP_FORBIDDEN];
        yield ['back/season/new', 'manager@manager.com', Response::HTTP_FORBIDDEN];
        yield ['back/casting/new', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/season/new', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/person/new', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/user/new', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/movie/1/edit', 'manager@manager.com', Response::HTTP_FORBIDDEN];
        yield ['back/movie/1/edit', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/casting/1/edit', 'manager@manager.com', Response::HTTP_FORBIDDEN];
        yield ['back/user/1/edit', 'manager@manager.com', Response::HTTP_FORBIDDEN];
        yield ['back/person/1/edit', 'manager@manager.com', Response::HTTP_FORBIDDEN];
        yield ['back/season/1/edit', 'manager@manager.com', Response::HTTP_FORBIDDEN];

    }

}
