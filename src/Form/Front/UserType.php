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
                    $builder->add('password', RepeatedType::class,[ 
                        'type' => PasswordType::class, 
                        "mapped" => false,
                        'invalid_message' => 'Les 2 mots de passe doivent être identiques',
                        'options' => ['attr' => ['class' => 'password-field']],
                        'first_options' => [ 
                                "label" => "Mot de passe",
                                "attr" => ["placeholder" => "Laissez vide pour ne pas modifier"],
                                'constraints' => [
                                    new Regex(
                                        "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/",
                                        "Le mot de passe doit contenir au minimum 8 caractères, une majuscule, un chiffre et un caractère spécial"
                                    ),
                                ],
                            ],
                        'second_options' => [
                            'label' => 'Répétez le mot de passe',
                            "attr" => ["placeholder" => "Laissez vide pour ne pas modifier"],
                            ],
                        ])
                    ;
                } else {
                    // * mode Création : New
                    $builder->add('password', RepeatedType::class,[
                        'type' => PasswordType::class, 
                        'options' => ['attr' => ['class' => 'password-field']],
                        'first_options' => [ 
                                "label" => "Mot de passe",
                                'constraints' => [
                                    new NotBlank(),
                                    new Regex(
                                        "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/",
                                        "Le mot de passe doit contenir au minimum 8 caractères, une majuscule, un chiffre et un caractère spécial"
                                    ),
                                ],
                            ],
                        'second_options' => [
                            'label' => 'Répétez le mot de passe',
                            ],
                        'invalid_message' => 'Les 2 mots de passe doivent être identiques'
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
