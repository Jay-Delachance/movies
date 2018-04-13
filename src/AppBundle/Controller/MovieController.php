<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Genre;
use AppBundle\Entity\Movie;
use AppBundle\Entity\VoteMovie;
use AppBundle\Form\CommentType;
use AppBundle\Form\VoteMovieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MovieController extends Controller
{
    //liste des films
    public function listAction(Request $request)
    {
        //on récupère le repository de la classe Movie
        //pour faire des requêtes SELECT
        $repo = $this->getDoctrine()->getRepository(Movie::class);

        $year = $repo->getAllYears();


        // requête SELECT
        // ordre de la requête : where, order by, limit, offset
        // on récupère le paramètre d'url 'genreId'
        $selectedGenre = $request->query->get('genreId'); // même objet requet cat que dans le fichier list twig
        $selectedYearMin = $request->query->get('yearMin');
        $selectedYearMax = $request->query->get('yearMax');
        $selectedKeyword = $request->query->get('keyword');

        $movies = $repo->findMoviesWithGenre($selectedGenre, $selectedYearMin, $selectedYearMax, $selectedKeyword);
        // dump($genre); -> pour afficher $genre dans la barre de debug

        // on appelle la classe genre pour pouvoir les récupérer
        $genreRepo = $this->getDoctrine()->getRepository(Genre::class);

        $genre = $genreRepo->findAll();


        return $this->render("movie/list.html.twig", [
            "posters" => $movies,
            "genres" => $genre,
            "years" => $year,
        ]);
    }


    // page de détail d'un film
    public function detailAction($slug, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Movie::class);

        // pas la peine d'écrire la fonction findOneBy dans le Repository car cette fonction existe déjà
        // on dit à la fonction de nous trouver un imdbId qui correspond à la variable $imdbId (dans un tableau)
        $movie = $repo->findOneBy(array('slug' => $slug));

        $voteRepo = $this->getDoctrine()->getRepository(VoteMovie::class);
        // on va rechercher le vote dont le user est... et le film est...
        $previousVote = $voteRepo->findOneBy([
                'user' => $this->getUser(),
                'movie' => $movie]
        );

        // on met le formulaire de notation ici
        $newVote = new VoteMovie();
        $voteForm = $this->createForm(VoteMovieType::class, $newVote);

        $voteForm->handleRequest($request);

        if ($voteForm->isSubmitted() && $voteForm->isValid()) {
            $newVote->setMovie($movie);

            // on hydrate le nouveau vote en renseignant toutes ses propriétés
            $newVote->setDateVoted(new \DateTime());
            $newVote->setUser($this->getUser());


            // on recalcule la note globale du film
            $movie->setVotes($movie->getVotes() + 1);
            $result = (($movie->getVotes() * $movie->getRating()) + $newVote->getVote()) / ($movie->getVotes());
            $movie->setRating($result);


            $em = $this->getDoctrine()->getManager();
            $em->persist($newVote);
            $em->persist($movie);
            $em->flush();

            $this->addFlash("success", "Merci pour votre note !");

            return $this->redirectToRoute("movie_detail", [
                "slug" => $movie->getSlug(),
                "rates" => $newVote
            ]);
        }


        // on met le formulaire de commentaire ici
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            // on renseigne le film dans le commentaire
            $comment->setMovie($movie);
            $comment->setUsername($this->getUser()->getUsername());
            $comment->setDateCreated(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $this->addFlash("success", "Merci pour votre commentaire !");

            return $this->redirectToRoute("movie_detail", [
                "slug" => $slug,
            ]);
        }


        // on affiche une erreur 404 s'il l'id n'est pas trouvé
        if (!$movie) {
            throw $this->createNotFoundException("Oups ! Cette idée n'existe pas !");
        }


        // récupérer les commentaires de cette idée)
        $commentRepo = $this->getDoctrine()->getRepository(Comment::class);
        //["movie" => $movie] -> on réfléchit en terme d'objet donc on on récupérer le movie qui a un movie
        $comments = $commentRepo->findBy(["movie" => $movie], ["dateCreated" => "DESC"], 20, 0);

        return $this->render("movie/detail.html.twig", [
            // affiche les détails du film
            "details" => $movie,
            "commentForm" => $commentForm->createView(),
            "voteMovieForm" => $voteForm->createView(),
            "comments" => $comments,
            "previousVote" => $previousVote,
        ]);
    }


    public function voteAction($imdbId)
    {
        $repo = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $repo->find($imdbId); // on récupère l'id

        // voir dans movie.php pour cette méthode
        $movie->incrementLikes();

        // récupère l'Entity Manager
        $em = $this->getDoctrine()->getManager();
        // on demande à doctrine de sauvegarder mon objet movie avec persist
        $em->persist($movie);
        // on exécute réelement la requête
        $em->flush();

        // on affiche un message sur la page suivante. Avec le type success, on affiche le message en vert
        $this->addFlash("success", "Merci d'avoir voté");

        // on indique le nom de la page vers laquelle on redirige l'utilisateur (la page détail de l'idée)
        return $this->redirectToRoute("movie_detail", ["slug" => $movie->getSlug()]);
    }


}
