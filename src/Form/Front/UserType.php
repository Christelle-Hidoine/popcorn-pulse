<?php

namespace App\Form\Front;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $builder = $event->getForm();
                /** @var User $user */
                $user = $event->getData();

                if ($user->getId() !== null) {
                    // * mode Edition
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
                    // * mode Création : New
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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
