<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Movie;
use AppBundle\Entity\VoteMovie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class VoteMovieController extends Controller
{
    public function postAction($imdbId)
    {

        // l'utilisateur ne peut liker s'il n'est pas connecté
        $this->denyAccessUnlessGranted("ROLE_USER");

        $voteRepo = $this->getDoctrine()->getRepository(VoteMovie::class);

        $repo = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $repo->find($imdbId);

        // on va rechercher le vote dont le user est... et le film est...
        $previousVote = $voteRepo->findOneBy(
            ['user' => $this->getUser(),
                'movie' => $movie]
        );


        // On vérifie si l'utilisateur a déjà voté
        if ($previousVote) {
            // on est en Ajax donc l'addflash ne fonctionnera pas. Il faut du json
            return $this->json([
                "status" => "already_voted",
            ]);
        } else {
            $movie->incrementLikes();

            $vote = new VoteMovie();


            // récupère l'Entity Manager
            $em = $this->getDoctrine()->getManager();
            // persist pour garder l'incrément
            $em->persist($movie);
            $em->persist($vote);
            // exécute la requête
            $em->flush();
        }

        return $this->json([
            "status" => "ok",
            "noteCount" => $movie->getVotes()
        ]);

        // on indique le nom de la page vers laquelle on redirige l'utilisateur (la page de la liste des idées mise à jour)
        //return $this->redirectToRoute("movie_detail",  ["slug" => $slug]);
    }


}