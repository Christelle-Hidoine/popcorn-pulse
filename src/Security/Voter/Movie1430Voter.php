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
        if (!$user instanceof UserInterface) {
            return false;
        }

        $dateDuJour = new DateTime("now");
        $heure = $dateDuJour->format("Gi");
        if ($heure > 2030){
            return false;
        }

        return true;
    }
}
