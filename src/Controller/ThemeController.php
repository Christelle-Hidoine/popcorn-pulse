<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThemeController extends AbstractController
{
    
    /**
     * ajout d'un thème Netflix/Allociné en session
     * 
     * @Route("/theme/add/", name="add_theme")
     *
     * @return Response
     */
    public function addTheme(Request $request, Cookie $cookie)
    {
    // TODO : je veux mettre en session le theme pour modifier le css en fonction du thème sélectionné
    // pour accéder à la session, il me faut la requete
    // on demande à Symfony l'objet Request
    // * injection de dépendance
    $session = $request->getSession();
    $cookieSession = $cookie->getValue();
    // $cookieSession->set('tagada','surprise');
    dump($cookieSession);

    if (!empty($session)) {
        $theme = $session->get('theme');
        if ($theme ==  'Netflix') {
            $session->remove('theme');
            // je supprime les données en session
            $session->clear();

            // j'enregistre en session le thème que l'utilisateur a sélectionné
            $session->set("theme", 'Allocine');
        } else {
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

}
