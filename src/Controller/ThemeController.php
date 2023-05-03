<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThemeController extends AbstractController
{
    /**
     * ajout d'un thème Allociné en session
     * 
     * @Route("/themeAllocine/add/", name="add_theme_allocine")
     *
     * @return Response
     */
    public function addAllocine(Request $request)
    {            
        
        // TODO : je veux mettre en session le theme pour modifier le css en fonction du thème sélectionné
        // pour accéder à la session, il me faut la requete
        // on demande à Symfony l'objet Request
        // * injection de dépendance
        $session = $request->getSession();

        // j'enregistre en session le thème que l'utilisateur a sélectionné
        $session->set("theme", 'Allocine');

        // ? je n'ai rien à afficher en particulier
        // je redirige l'utilisateur vers la page d'accueil
        // la méthode redirectToRoute() me fournit une Response
        // je renvois de suite cette response
        return $this->redirectToRoute('default');
    }

    /**
     * ajout d'un thème Netflix en session
     * 
     * @Route("/themeNetflix/add/", name="add_theme_netflix")
     *
     * @return Response
     */
    public function addNetflix(Request $request)
    {            
        // TODO : je veux mettre en session le theme pour modifier le css en fonction du thème sélectionné
        // pour accéder à la session, il me faut la requete
        // on demande à Symfony l'objet Request
        // * injection de dépendance
        $session = $request->getSession();
        
        dump($session);
        if ($session == ['theme' => 'Netflix']) {
            $session->remove('theme');
            // je supprime les données en session
            $session->clear();

            // j'enregistre en session le thème que l'utilisateur a sélectionné
            $session->set("theme", 'Allocine');
        }
        else {
            $session->remove('theme');
            // je supprime les données en session
            $session->clear();
            $session->set("theme", 'Netflix');
        }
        // ? je n'ai rien à afficher en particulier
        // je redirige l'utilisateur vers la page d'accueil
        // la méthode redirectToRoute() me fournit une Response
        // je renvois de suite cette response
        return $this->redirectToRoute('default');
    }

}
