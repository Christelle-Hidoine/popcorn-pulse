<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\RequestStack;

class ThemeManager 
{
    /** @var SessionInterface $session */
    private $session;

    /**
     * Injection de dépendance Request pour récupérer la session
     *
     * @param Request $request
     */
    public function __construct(RequestStack $request)
    {
        $this->session = $request->getSession();
    }

    public function addThemeSession()
    {
        if ($this->session->get('theme')) {
            $theme = $this->session->get('theme');
            if ($theme == 'Netflix') {
                   // j'enregistre en session le thème que l'utilisateur a sélectionné
                $this->session->set("theme", 'Allocine');
            } else {
                $this->session->set("theme", 'Netflix');
            }
        } else {
            $this->session->set("theme", 'Allocine');
        }

        return $this->session->get('theme');
    }
}