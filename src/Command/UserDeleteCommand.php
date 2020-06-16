<?php

namespace App\Command;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserDeleteCommand extends Command
{
    private $userRepository;

    /**
     * Le client HTTP utilisé dans la commande
     */
    private $client;

    private $entityManager;

    protected static $defaultName = 'app:delete-users';

    /**
     * cf : https://symfony.com/doc/current/console.html#getting-services-from-the-service-container
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Delete users with status 0.')
            ->setHelp('This command allows you to delete a user...')
            ->addOption('dump', null, InputOption::VALUE_NONE, 'Displays more information');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return Command::SUCCESS;
        // Classe qui permet d'écrire joli dans le terminal
        $io = new SymfonyStyle($input, $output);

        // Aller chercher nos films
        $movies = $this->movieRepository->findAll();

        // On boucle sur nos films
        foreach ($movies as $movie) {
            // On va chercher l'URL du film
            $url = $this->getPosterUrlFromMovie($movie);
            // Si trouvée on télécharge le poster
            if ($url !== null) {
                // On download et on récupère le nom du fichier téléchargé
                $filename = $this->downloadFromUrl($url);
                // On ajoute le filename au film concerné
                $movie->setPoster($filename);
                // On update le film en BDD
                $this->entityManager->flush();

                if ($input->getOption('dump')) {
                    $io->note('Poster téléchargé pour ' . $movie->getTitle());
                }
            }
        }

        // La commande nous recommande cette valeur de retour
        return 0;
    }

    /**
     * Va chercher une URL de poster selon un film donné
     *
     * @param Movie $movie Le film pour lequel trouver l'URL du poster
     *
     * @return string|null L'URL ou null si film non trouvé
     */
    private function getPosterUrlFromMovie(Movie $movie): ?string
    {
        // On encode au format URL notre titre
        // pour ne pas casser la requête
        $titleUrlEncoded = urlencode($movie->getTitle());
        // On crée un client HTTP
        // cf : https://symfony.com/doc/current/components/http_client.html#basic-usage
        $this->client = HttpClient::create();
        // @todo : mettre la clé dans une propriété de la classe
        $response = $this->client->request(
            'GET',
            'http://www.omdbapi.com/?t=' . $titleUrlEncoded . '&apikey=bbac9560'
        );

        // On décode le JSON reçu, on reçoit un objet !
        $movieData = json_decode($response->getContent());
        //dump($movieData);

        return $movieData->Poster;
    }

    /**
     * Télécharge l'image depuis l'URL fournie
     *
     * @param string $posterUrl
     *
     * @return string Nom du fichier sauvegardé
     */
    private function downloadFromUrl(string $posterUrl)
    {
        // On va chercher l'image trouvée
        $response = $this->client->request('GET', $posterUrl);
        // Le contenu de l'image se trouve dans le corps de la réponse
        $imageContent = $response->getContent();
        // On sauvegarde ce contenu (en PHP natif)
        // 0. Créons un nom unique pour notre fichier
        $filename = uniqid('movie', true) . '.jpg';
        // 1. On ouvre un fichier en lecture
        $fileHandler = fopen($this->postersDirectory . $filename, 'w');
        // 2. On écrit le contenu récupéré (en mémoire) dans le fichier sur notre disque
        fwrite($fileHandler, $imageContent);
        // 3. On ferme le fichier (sinon on ne pourra pas y accéder)
        fclose($fileHandler);

        // On retourne le nom du fichier pour sauvegarde dans l'entité
        return $filename;
    }
}
