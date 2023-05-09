<?php

namespace App\Form;

use App\Entity\Casting;
use App\Entity\Movie;
use App\Entity\Person;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CastingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('role', TypeTextType::class, ["label" => "Rôle", "attr" => ["placeholder" => "personnage joué dans le film ou la série"]])
            ->add('creditOrder', IntegerType::class, ["label" => "Classement par importance du rôle", 'attr' => [
                'min' => 1
            ]])
            ->add('movies', EntityType::class, [
                // * c'est un ChoiceType : multiple + expanded
                "multiple" => false,
                "expanded" => false, 
                // * EntityType : class est obligatoire
                "class" => Movie::class,
                // * EntityType : choice_label est obligatoire
                'choice_label' => 'title',
                'label' => "Film ou Série",
            ])
            ->add('persons', EntityType::class, [
                // * c'est un ChoiceType : multiple + expanded
                "multiple" => false,
                "expanded" => false, 
                // * EntityType : class est obligatoire
                "class" => Person::class,
                // * EntityType : choice_label est obligatoire
                'choice_label' => 'fullName',
                'label' => "Acteur / Actrice",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Casting::class,
        ]);
    }
}
