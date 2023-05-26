<?php

namespace App\DataFixtures;

use App\Entity\Casting;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Review;
use App\Entity\Season;
use App\Entity\Type;
use App\Entity\User;
use App\Services\OmdbApi;
use Bluemmb\Faker\PicsumPhotosProvider;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use \Xylis\FakerCinema\Provider\Movie as FakerMovieProvider;
use Xylis\FakerCinema\Provider\Person as FakerPersonProvider;
use Xylis\FakerCinema\Provider\TvShow as FakerTvShowProvider;
use \Xylis\FakerCinema\Provider\Character as FakerCharacterProvider;

class Oflix extends Fixture
{
    /**
     * Service OmdbApi
     *
     * @var OmdbApi
     */
    private $omdbApi;

    public function __construct(OmdbApi $omdbApi)
    {
        $this->omdbApi = $omdbApi;
    }

    /**
     * Création de donnée
     *
     * @param ObjectManager
     */
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();
        $fakerFr = \Faker\Factory::create('fr_FR');
        $faker->addProvider(new PicsumPhotosProvider($faker));
        $faker->addProvider(new FakerMovieProvider($faker));
        $faker->addProvider(new FakerPersonProvider($faker));
        $faker->addProvider(new FakerTvShowProvider($faker));
        $faker->addProvider(new FakerCharacterProvider($faker));

        $admin = new User();
        $admin->setEmail("admin@admin.com");
        $admin->setPassword('$2y$13$UX6UDREB8cdTuNVt3i9QcOFcyFqcQbCk.yh.D9rgYHJzs4GrfD/w.');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setFirstname('Admin');
        $admin->setLastname('Admin');

        $manager->persist($admin);

        $managerUser = new User();
        $managerUser->setEmail("manager@manager.com");
        $managerUser->setPassword('$2y$13$ehwmxDazwOE8ol3eTRz/C.YapEQ8UMyDFzolfGCg97gegVtOwjXu6');
        $managerUser->setRoles(['ROLE_MANAGER']);
        $managerUser->setFirstname('ManagerUser');
        $managerUser->setLastname('ManagerUser');

        $manager->persist($managerUser);


        $user = new User();
        $user->setEmail("user@user.com");
        $user->setPassword('$2y$13$J9VkB737ouoPOiH0oTGNQOlvqxZ6Hz95mZiubq/kFzgJ2B7nt608m');
        $user->setRoles(['ROLE_USER']);
        $user->setFirstname('User');
        $user->setLastname('User');

        $manager->persist($user);

        $genres = ["Action", "Animation", "Aventure", "Comédie", "Dessin Animé", "Documentaire", "Drame", "Espionnage", "Famille", "Fantastique", "Historique", "Policier", "Romance", "Science-fiction", "Thriller", "Western"];
        /** @var Genre[] $allGenre */
        $allGenre = [];
        foreach ($genres as $genreName) {
            $newGenre = new Genre();
            $newGenre->setName($genreName);
            $manager->persist($newGenre);
            $allGenre[] = $newGenre;
        }

        $types = ["film", "série"];
        /** @var Type[] $allTypes */
        $allTypes = [];
        foreach ($types as $type) {
            $newType = new Type();
            $newType->setName($type);
            $manager->persist($newType);
            $allTypes[] = $newType;
        }

        /** @var Person[] $allPerson */
        $allPerson = [];
        for ($i=0; $i < 1000; $i++) { 
            $newPerson = new Person();
            $actorFullName = $faker->actor();
            $actorNames = explode(" ", $actorFullName);
            $newPerson->setFirstname($actorNames[0]);
            $newPerson->setLastname($actorNames[1]);

            $manager->persist($newPerson);

            $allPerson[] = $newPerson;

        }

        /** @var Movie[] $allMovies */
        $allMovies = [];
        for ($i=0; $i < 100; $i++) { 
            $newMovie = new Movie();
        
            $randomType = $allTypes[mt_rand(0, count($allTypes)-1)];
            $newMovie->setType($randomType);

            if ($randomType->getName() === "série"){
                $titleTvShow = $faker->tvShow();
                $contentTvShow = $this->omdbApi->fetch($titleTvShow);
                $newMovie->setTitle($titleTvShow);

                $posterTvShow = $contentTvShow->getPoster();
                $newMovie->setPoster($posterTvShow);

                $summaryTvShow = $contentTvShow->getPlot();
                $newMovie->setSummary($summaryTvShow);

                $countryTvShow = $contentTvShow->getCountry();
                $newMovie->setCountry($countryTvShow);

                $newMovie->setReleaseDate(new DateTime($contentTvShow->getReleased()));

                $newMovie->setDuration($contentTvShow->getRuntime());

            } else {

                $titleMovie = $faker->movie();
                $contentMovie = $this->omdbApi->fetch($titleMovie);
                $newMovie->setTitle($titleMovie);

                $posterMovie = $contentMovie->getPoster();
                $newMovie->setPoster($posterMovie);

                $summaryMovie = $contentMovie->getPlot();
                $newMovie->setSummary($summaryMovie);

                $countryMovie = $contentMovie->getCountry();
                $newMovie->setCountry($countryMovie);

                $newMovie->setReleaseDate(new DateTime($contentMovie->getReleased()));

                $newMovie->setDuration($contentMovie->getRuntime());
            }

            $newMovie->setRating(mt_rand(0,50) / 10);

            $newMovie->setSynopsis($fakerFr->realText(200,2));

            $manager->persist($newMovie);

            $allMovies[] = $newMovie;
        }

        foreach ($allMovies as $movie) {
            $randomNbCasting = mt_rand(3,5);
            for ($i=1; $i <= $randomNbCasting; $i++) { 
                $newCasting = new Casting();
                $newCasting->setRole($faker->character());
                $newCasting->setCreditOrder($i);
                $newCasting->setMovies($movie);
                $randomPerson = $allPerson[mt_rand(0, count($allPerson)-1)];
                $newCasting->setPersons($randomPerson);
                $manager->persist($newCasting);
            }
        }

        foreach ($allMovies as $movie) {
            $randomNbGenre = mt_rand(1,3);
            for ($i=0; $i <= $randomNbGenre; $i++) { 
                $randomGenre = $allGenre[mt_rand(0, count($allGenre)-1)];
                $movie->addGenre($randomGenre);
            }
        }

        foreach ($allMovies as $movie) {
            if ($movie->getType()->getName() == "série")
            {
                $randomNbSeason = mt_rand(3,10);
                for ($i=1; $i <= $randomNbSeason; $i++) { 
                    $newSeason = new Season();
                    $newSeason->setNumber($i);
                    $newSeason->setNbEpisodes(mt_rand(12, 24));
                    $movie->addSeason($newSeason);

                    $manager->persist($newSeason);
                }
            }
        }

        $reactions = ['cry', 'smile', 'dream', 'think', 'sleep'];
        $rating = [];
        foreach ($allMovies as $movie) {
            $randomNbReview = mt_rand(0, 5);
            
            for ($i=0; $i < $randomNbReview; $i++) {
                /** @var Review $newReview */
                $newReview = new Review();
                $newReview->setUsername($faker->userName());
                $newReview->setEmail($faker->email());
                $newReview->setContent($fakerFr->realText(30, 1));
                $newReview->setRating($faker->numberBetween(1,5));
                $randomNbReaction = $faker->numberBetween(0, count($reactions) -1);
                $newReview->setReactions([$reactions[$randomNbReaction]]);
                $newReview->setWatchedAt(new DateTimeImmutable($faker->date()));

                $rating[] = $newReview->getRating();
                $sum = array_sum($rating);
                $count = count($rating);
                $average = round($sum/$count, 1);
                $movie->setRating($average);
                $newReview->setMovie($movie);

                $manager->persist($newReview);

            }
        }

        $manager->flush();
    }
}
