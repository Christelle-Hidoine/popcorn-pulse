<?php

namespace App\Controller\Front;

use App\Services\ThemeManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function addTheme(ThemeManager $theme)
    {
    // TODO : je veux mettre en session le theme pour modifier le css en fonction du thème sélectionné
    // * utilisation du service ThemeManager
    $theme->addThemeSession();

    return $this->redirectToRoute('default');

    }

}
