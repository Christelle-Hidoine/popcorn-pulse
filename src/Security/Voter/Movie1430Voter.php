<?php

namespace App\Security\Voter;

use App\Entity\Movie;
use DateTime;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class Movie1430Voter extends Voter
{
    /**
     * Est ce que la class sait gérer ce droit ?
     *
     * @param string $attribute le nom du droit demandé
     * @param [type] $subject un objet pour contextualiser le droit
     * @return boolean
     */
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        // si le droit se nomme MOVIE_1430 et le contexte est un objet Movie = return true
        if ($attribute === "MOVIE_2030" && $subject instanceof Movie) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * est ce que j'ai le droit ?
     *
     * @param string $attribute
     * @param [type] $subject
     * @param TokenInterface $token
     * @return boolean
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        $dateDuJour = new DateTime("now");
        // ? https://www.php.net/manual/fr/datetime.format.php
        // $heure =>  810, 1430
        $heure = $dateDuJour->format("Gi");
        if ($heure > 2030){
            return false;
        }

        return true;
    }
}
