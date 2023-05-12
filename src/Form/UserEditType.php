<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Validator\Constraints\Regex;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $roles = [
            // role Membre enregistré par défaut dans l'entity USER
            "Membre" => 'ROLE_USER',
            "Manager" => 'ROLE_MANAGER',
            "Administrateur" => 'ROLE_ADMIN',
            ];

        $builder
            ->add('email', EmailType::class, [
                "attr" => ["placeholder" => "user@cinema.com"]
            ])
            ->add('roles', ChoiceType::class, [
                "multiple" => true,
                "expanded" => true,
                "choices" => [
                    "Administrateur" => "ROLE_ADMIN",
                    "Manager" => "ROLE_MANAGER",
                    "Membre" => "ROLE_USER",
                ],
                "label" => "Rôles", "help" => "(un seul choix possible)"
            ])
            ->add('password', PasswordType::class, [
                // je ne veux pas que le formulaire mettes automatiquement à jour la valeur
                // je désactive la mise à jour automatique de mon objet par le formulaire
                "mapped" => false,
                "label" => "le mot de passe",
                "attr" => [
                    "placeholder" => "laisser vide pour ne pas modifier ..."
                ],
                // On déplace les contraintes de l'entité vers le form d'ajout
                'constraints' => [
                    new Regex(
                        "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/",
                        "Le mot de passe doit contenir au minimum 8 caractères, une majuscule, un chiffre et un caractère spécial"
                    ),
                ],
            ])
        ;
        // // Data transformer
        // $builder->get('roles')
        //     ->addModelTransformer(new CallbackTransformer(
        //         function ($rolesArray) {
        //             //  transform the array to a string
        //              return count($rolesArray)? $rolesArray[0]: null;
        //         },
        //         function ($rolesString) {
        //             //  transform the string back to an array
        //              return [$rolesString];
        //         }
        // ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            "attr" => ["novalidate" => "novalidate"]
        ]);
    }
}