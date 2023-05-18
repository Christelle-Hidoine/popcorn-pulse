<?php

namespace App\Form\Back;

use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TypeTextType::class, ["label" => "Prénom", "attr" => ["placeholder" => "prénom de l'acteur/actrice"]])
            ->add('lastname', TypeTextType::class, ["label" => "Nom", "attr" => ["placeholder" => "nom de l'acteur/actrice"]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}
