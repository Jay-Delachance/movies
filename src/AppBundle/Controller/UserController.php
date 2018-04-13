<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Movie;
use AppBundle\Entity\User;
use AppBundle\Entity\WatchlistItem;
use AppBundle\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends Controller
{
    // page d'inscription (création de compte)
    public function registerAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        // crée un nouveau User vide
        $user = new User();
        // crée le formulaire en l'associant au User vide
        $registerForm = $this->createForm(RegisterType::class, $user);

        // prend les données de la requête et les injecte dans notre User vide
        $registerForm->handleRequest($request);

        // si le formulaire est soumis et valide...
        if ($registerForm->isSubmitted() && $registerForm->isValid()) {

            // hash le mot de passe
            $hash = $encoder->encodePassword($user, $user->getPassword());
            // remplace le mot de passe en clair par le hash
            // avant la sauvegarde en bdd
            $user->setPassword($hash);

            // renseigne le rôle et la date, toujours pareil (un admin ne se crééera pas en passant par le front office
            $user->setRoles("ROLE_USER");
            $user->setDateRegistered(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "Bienvenue !");
            return $this->redirectToRoute("login");
        }

        // affiche la page
        return $this->render("user/register.html.twig", [
            // passe le formulaire à twig pour l'affichage
            "registerForm" => $registerForm->createView()]);
    }


    public function loginAction(AuthenticationUtils $authUtils)
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('user/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }


    public function addAction($id)
    {
        // on a accès à cette fonction uniquement si on est connecté
        $this->denyAccessUnlessGranted("ROLE_USER");

        // accès à la table movie de la base de donnée
        $repo = $this->getDoctrine()->getRepository(Movie::class);
        // on récupère le user connecté
        $connected = $this->getUser();
        // on récupère l'id du film sur lequel on est
        $movie = $repo->find($id);

        // on va chercher le repository de la classe WatchlistItem
        $wRepo = $this->getDoctrine()->getRepository(WatchlistItem::class);

        // on crée une variable qui va représenter l'objet WatchlistItem sur lequel on se trouve et que l'on veut supprimer
        $previousWMovie = $wRepo->findOneBy([
            // on récupère le user connecté et le film dans un tableau
            "user" => $this->getUser(),
            "movie" => $movie
        ]);

        if ($previousWMovie == null) {
            // on crée un nouvel objet WatchlistItem qui va s'intégrer dans la page de la watchlist
            $wItem = new WatchlistItem();
            // on set la date, le film et le user (les paramètres de l'objet)
            $wItem->setDateCreated(new \DateTime());
            $wItem->setMovie($movie);
            $wItem->setUser($connected);

            // récupère l'Entity Manager
            $em = $this->getDoctrine()->getManager();
            // on demande à doctrine de sauvegarder mon objet wItem avec persist
            $em->persist($wItem);
            // on exécute réelement la requête
            $em->flush();

            // ça nous affiche ok dans l'inspecteur du navigateur
            die('ok');

        } else {
            die('not');
        }


        /*
                return $this->render(user/watchlist.html.twig, [
                    // affiche les détails du film
                    "previousWMovie" => $previousWMovie,
                ]);*/

    }


    public function watchlistAction()
    {
        // on a accès à cette fonction uniquement si on est connecté
        $this->denyAccessUnlessGranted("ROLE_USER");
        //on récupère le repository de la classe Movie
        //pour faire des requêtes SELECT
        $repo = $this->getDoctrine()->getRepository(Movie::class);
        // on récupère le user connecté
        $connected = $this->getUser();
        // on appelle la fonction findMoviesWatch du repository et on lui passe le user connecté
        $movies = $repo->findMoviesWatch($connected);

        // dans la page watchlist, on affiche les posters de films de la watchlist
        return $this->render("user/watchlist.html.twig", [
            "posters" => $movies
        ]);
    }


    public function deleteAction($id)
    {
        // on a accès à cette fonction uniquement si on est connecté
        $this->denyAccessUnlessGranted("ROLE_USER");

        //on récupère le repository de la classe Movie
        //pour faire des requêtes SELECT
        $repo = $this->getDoctrine()->getRepository(Movie::class);

        // on récupère l'id du film sur lequel on est
        $movie = $repo->find($id);

        // on va chercher le repository de la classe WatchlistItem
        $wRepo = $this->getDoctrine()->getRepository(WatchlistItem::class);
        // on crée une variable qui va représenter l'objet WatchlistItem sur lequel on se trouve et que l'on veut supprimer
        $wMovie = $wRepo->findOneBy([
            // on récupère le user connecté et le film dans un tableau
            "user" => $this->getUser(),
            "movie" => $movie
        ]);

        if ($wMovie != null) {
            // récupère l'Entity Manager
            $em = $this->getDoctrine()->getManager();
            // on demande à doctrine de supprimer mon objet $wMovie
            $em->remove($wMovie);
            // on exécute réelement la requête
            $em->flush();

            die('ok');
        } else {
            die('not');
        }


    }


}
