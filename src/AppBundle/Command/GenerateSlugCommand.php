<?php

namespace AppBundle\Command;

use AppBundle\Entity\Movie;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateSlugCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            // on donne un nom à la commande
            ->setName('GenerateSlugsMovies')
            // description de la commande
            ->setDescription('Generate slugs for movies')
            // aide de la commande
            ->setHelp('Aide');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // on va chercher doctrine
        $doctrine = $this->getContainer()->get("doctrine");

        // on appelle le manager
        $em = $doctrine->getManager();

        // appelle le MovieRepository pour pouvoir appeler la fonction sql findAll
        $repo = $doctrine->getRepository(Movie::class);

        $movies = $repo->findAll();

        // on fait une boucle qui s'arrête au total du nombre de films
        for ($i = 0; $i < count($movies); $i++) {
            // à chaque tour de boucle, on crée un nouveau slug
            $slugify = new Slugify();
            // on transforme en slug chaque titre de film et on le concatène à l'année du film (plusieurs film ont le même titre)
            $slug = $slugify->slugify($movies[$i]->getTitle() . "-" . $movies[$i]->getYear());
            // on affecte le slug à la variable movies
            // attention à bien créer une variable private $slug dans le fichier Movie.php et à générer les getters et setters
            $movies[$i]->setSlug($slug);
            // enregistre dans la base de données les slugs
            $em->persist($movies[$i]);
        }

        // exécute la commande
        $em->flush();

        // affichage d'un message après la fin de l'exécution de la commande
        $output->writeln("Slugs of movies updated.");
    }
}

// Bien penser à modifier les fichiers routing et list.html.twig en remplaçant imdbId par slug aux endroits nécessaires
