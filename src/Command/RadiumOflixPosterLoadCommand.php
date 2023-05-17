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
        // ! Command class "App\Command\RadiumOflixPosterLoadCommand" is not correctly initialized. You probably forgot to call the parent constructor. 
        $this->client = $httpClientInterface;
        $this->em = $em;
        // (un coup de fil à mamie)
        parent::__construct();
    }

    protected static $defaultName = 'radium:oflix:poster-load';
    // on peut modifier ces données ici
    protected static $defaultDescription = 'met à jour tout les poster de la BDD avec OMDBAPI';

    protected function configure(): void
    {
        $this
            ->addArgument(
                // le nom du paramètre, va nous servir pour récupérer la valeur dans la fonction execute
                'movie_id',
                // est ce que l'on est obligé de fournir l'argument ?
                // OUI : InputArgument::REQUIRED
                // NON : InputArgument::OPTIONAL
                InputArgument::OPTIONAL,
                // le message de decription pour l'aide
                'ID du film à mettre à jour')

            //->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        // ? https://symfony.com/doc/5.4/console/style.html
        // permet d'avoir accès a plein de méthodes utilitaires dans le terminal
        $io = new SymfonyStyle($input, $output);
        
        $movieID = $input->getArgument('movie_id');
        // puisque il est optionnel, je vérifie sa présence
        if ($movieID) {
            // j'ai l'argument fourni
            // TODO : aller chercher le film
            $movie = $this->movieRepository->find($movieID);
            // 2. on demande à L'API
            $response = $this->client->request(
                // 1. la méthode
                'GET',
                // 2. URL
                // on va créer l'URL avec le titre du film
                "https://www.omdbapi.com/?apikey=" . $this->apiKey . "&t=". $movie->getTitle()
            );
            // je récupère le contenu de la réponse en tableau associatif
            $content = $response->toArray();

            if (array_key_exists('Poster', $content)){
                $newPoster = $content['Poster'];
            } else {
                // il n'y pas de lien pour le poster
                // on met une URL par défaut
                $newPoster = "https://amc-theatres-res.cloudinary.com/amc-cdn/static/images/fallbacks/DefaultOneSheetPoster.jpg";
            }

            // 3t. on met à jour notre poster de notre film
            $movie->setPoster($newPoster);
            // 4. on flush
            $this->em->flush();

            $io->success('Le film' . $movie->getTitle() . ' a bien été mis à jour');
            return Command::SUCCESS;
        }

        // TODO : aller chercher dans OMDBAPI les posters de chaque film
        // 1. il nous faut la liste des films : BDD, Repository
        $movies = $this->movieRepository->findAll();
        // 2. on boucle sur la liste des films
        foreach ($movies as $movie) {
            // UX
            $io->title($movie->getTitle());
            // 3. pour chaque film, on demande à L'API
            // pour faire des requetes HTTP, on utilise le service HttpClient = injection de dépendance
            $response = $this->client->request(
                // 1. la méthode
                'GET',
                // 2. URL
                // on va créer l'URL avec le titre du film
                "https://www.omdbapi.com/?apikey=" . $this->apiKey . "&t=". $movie->getTitle()

            );
            //dd($response);
            $statusCode = $response->getStatusCode();
            // dd($statusCode);// $statusCode = 200
            $contentType = $response->getHeaders()['content-type'][0];
            // dd($contentType);// $contentType = 'application/json; charset=utf-8'
            $content = $response->getContent();
            //dd($content);
            // $content = '{"id":521583, "name":"symfony-docs", ...}'
            $content = $response->toArray();
            // dd($content);
            // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
            if (array_key_exists('Poster', $content)) {
                // 3b. on récupère l'URL du poster
                $posterUrl = $content['Poster'];
            } else {
                // il n'y pas de lien pour le poster
                // on met une URL par défaut
                $posterUrl = "https://amc-theatres-res.cloudinary.com/amc-cdn/static/images/fallbacks/DefaultOneSheetPoster.jpg";
            }
            // 3t. on met à jour notre poster de notre film
            $movie->setPoster($posterUrl);
        }
        // 4. on flush
        $this->em->flush();
        
        $io->success('Les posters des films ont été ajouté avec succès ! Félicitations ');
        return Command::SUCCESS;

        /* Gestion des arguments / options
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }
        */
    }
}
