<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('email', EmailType::class, [
                "attr" => ["placeholder" => "user@cinema.com"]
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                $roles],
                "expanded" => true,
                "multiple" => true,
                "label" => "Rôle", "help"  => "(plusieurs choix possible)"
            ])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe', 'help' => 'Le mot de passe doit contenir au minimum 4 caractères'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
