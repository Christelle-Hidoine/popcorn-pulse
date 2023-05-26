<?php

namespace App\Command;

use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RadiumOflixPosterLoadCommand extends Command
{
    /** @var MovieRepository  $movieRepository */
    private $movieRepository;

    /** @var HttpClientInterface $client pour faire des requêtes HTTP */
    private $client;

    /** @var EntityManagerInterface $em uniquement pour le flush */
    private $em;

    /** @var string $apiKey clé de notre API */
    private $apiKey = "5cba044d";

    public function __construct(MovieRepository $movieRepository, HttpClientInterface $httpClientInterface, EntityManagerInterface $em)
    {
        // ? https://symfony.com/doc/5.4/http_client.html

        $this->movieRepository = $movieRepository;
        $this->client = $httpClientInterface;
        $this->em = $em;
        parent::__construct();
    }

    protected static $defaultName = 'radium:oflix:poster-load';
    protected static $defaultDescription = 'met à jour tout les poster de la BDD avec OMDBAPI';

    protected function configure(): void
    {
        $this
            ->addArgument(
                'movie_id',
                InputArgument::OPTIONAL,
                'ID du film à mettre à jour')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $io = new SymfonyStyle($input, $output);
        $movieID = $input->getArgument('movie_id');
        if ($movieID) {
            // TODO : aller chercher le film
            $movie = $this->movieRepository->find($movieID);
            $response = $this->client->request(
                'GET',
                "https://www.omdbapi.com/?apikey=" . $this->apiKey . "&t=". $movie->getTitle()
            );
            $content = $response->toArray();

            if (array_key_exists('Poster', $content)){
                $newPoster = $content['Poster'];
            } else {
                $newPoster = "https://amc-theatres-res.cloudinary.com/amc-cdn/static/images/fallbacks/DefaultOneSheetPoster.jpg";
            }

            $movie->setPoster($newPoster);
            $this->em->flush();

            $io->success('Le film' . $movie->getTitle() . ' a bien été mis à jour');
            return Command::SUCCESS;
        }

        $movies = $this->movieRepository->findAll();
        foreach ($movies as $movie) {
            $io->title($movie->getTitle());
            $response = $this->client->request(
                'GET',
                "https://www.omdbapi.com/?apikey=" . $this->apiKey . "&t=". $movie->getTitle()
            );

            $content = $response->getContent();
            $content = $response->toArray();
            if (array_key_exists('Poster', $content)) {
                $posterUrl = $content['Poster'];
            } else {
                $posterUrl = "https://amc-theatres-res.cloudinary.com/amc-cdn/static/images/fallbacks/DefaultOneSheetPoster.jpg";
            }
            $movie->setPoster($posterUrl);
        }
        $this->em->flush();
        
        $io->success('Les posters des films ont été ajouté avec succès ! Félicitations ');
        return Command::SUCCESS;

    }
}
