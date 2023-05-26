<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\RequestStack;

class ThemeManager 
{
    /** @var SessionInterface $session */
    private $request;

    /**
     * Injection de dépendance Request pour récupérer la session
     *
     * @param Request $request
     */
    public function __construct(RequestStack $request)
    {
        $this->request = $request;
    }

    public function addThemeSession()
    {
        $session = $this->request->getSession();
        if ($session->get('theme')) {
            $theme = $session->get('theme');
            if ($theme == 'Netflix') {
                $session->set("theme", 'Allocine');
            } else {
                $session->set("theme", 'Netflix');
            }
        } else {
            $session->set("theme", 'Allocine');
        }

        return $session->get('theme');
    }
}