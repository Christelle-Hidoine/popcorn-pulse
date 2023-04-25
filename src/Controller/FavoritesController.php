<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoritesController extends AbstractController
{
    /**
     * @Route("/favoris", name="movie_favorites")
     */
    public function favorites(Request $request): Response
    {
        // TODO : stoker en session les favoris
        // ? où se trouve la session ? dans le cookies de la requete
        // ? où se trouve les informations qui proviennent de la requete ?
        // dans symfony il y un objet Request, tout comme il y a un objet Reponse
        // ? Comment on obtient cet objet Request ?
        // new Request();
        // ! ce n'est pas une bonne idée, car on devrait pas créer nous même une requete
        // il faut demander à symfony, c'est lui qui gère/reçoit la requete
        // Pour demander un objet à Symfony, il suffit de l'ajouter en argument de notre function
        // avec le type hinting Symfony va savoir de quel objet on a besoin
        // dd($request);
        // * cette façon de faire est utilisé dans plusieurs language
        // * cela s'appele l'injection de dépendance
        $session = $request->getSession();
        // dd($session);
        // $session->set('favoris', "Vive les Radium");
         
        return $this->render('favorites/index.html.twig', ['session' => $session]);
    }
}
