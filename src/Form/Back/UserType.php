<?php

namespace App\Form\Back;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $roles = [
            "Membre" => 'ROLE_USER',
            "Manager" => 'ROLE_MANAGER',
            "Administrateur" => 'ROLE_ADMIN',
            ];

        $builder
            ->add('firstname', TextType::class, ['label' => 'Prénom'])
            ->add('lastname', TextType::class, ['label' => 'Nom'])
            ->add('email', EmailType::class, [
                "attr" => ["placeholder" => "user@cinema.com"]
            ])
            ->add('roles', ChoiceType::class, [
                "multiple" => false,
                "expanded" => true,
                "choices" => [
                    "Administrateur" => "ROLE_ADMIN",
                    "Manager" => "ROLE_MANAGER",
                    "Membre" => "ROLE_USER",
                ],
                "label" => "Rôles", "help" => "(un seul choix possible)"
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                $builder = $event->getForm();
                /** @var User $user */
                $user = $event->getData();

                if ($user->getId() !== null) {
                    $builder->add('password', PasswordType::class, [
                            "mapped" => false,
                            "label" => "le mot de passe",
                            "attr" => [
                                "placeholder" => "laisser vide pour ne pas modifier ..."
                            ],
                            'constraints' => [
                                new Regex(
                                    "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/",
                                    "Le mot de passe doit contenir au minimum 8 caractères, une majuscule, un chiffre et un caractère spécial"
                                ),
                            ],
                        ])
                    ;
                } else {
                    $builder->add('password', PasswordType::class, [
                                "label" => "Mot de passe",
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
        
        // Data transformer
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    //  transform the array to a string
                    return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                    //  transform the string back to an array
                    return [$rolesString];
                }
        ));
    }

    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
