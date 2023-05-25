<?php

namespace App\Form\Front;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, ['label' => 'Prénom'])
            ->add('lastname', TextType::class, ['label' => 'Nom'])
            ->add('email', EmailType::class, [
                "attr" => ["placeholder" => "user@cinema.com"]
            ])
            // on utilise l'event avant de mettre les données dans le formulaire
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                // On récupère le form depuis l'event (pour travailler avec)
                $builder = $event->getForm();
                // On récupère le user mappé sur le form depuis l'event
                /** @var User $user */
                $user = $event->getData();

                // On conditionne le champ "password"
                // Si user existant, il a id non null
                if ($user->getId() !== null) {
                    // * mode Edition
                    $builder->add('password', PasswordType::class, [
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
                } else {
                    // * mode Création : New
                    $builder->add('password', PasswordType::class, [
                                "label" => "Mot de passe",
                                // On déplace les contraintes de l'entité vers le form d'ajout
                                'constraints' => [
                                    new NotBlank(),
                                    new Regex(
                                        "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/",
                                        "Le mot de passe doit contenir au minimum 8 caractères, une majuscule, un chiffre et un caractère spécial"
                                    ),
                                ],
                            ])
                    ;
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
