<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('duration')
            ->add('rating')
            ->add('summary')
            ->add('synopsis')
            ->add('releaseDate')
            ->add('country')
            ->add('poster')
            // j'ai une relation avec une autre Entité
            // l'élément HTML désiré : Choix : ChoiceType
            // on veux un choiceType spécialisé pour les entités : EntityType
            // ? https://symfony.com/doc/5.4/reference/forms/types/entity.html
            ->add('type', EntityType::class, [
                // * c'est un ChoiceType : multiple + expanded
                "multiple" => false,
                "expanded" => false, // radiobutton
                // ! The required option "class" is missing.
                // ? à quelle entité est on lié ?
                "class" => Type::class,
                // ! Object of class Proxies\__CG__\App\Entity\Type could not be converted to string
                // on doit préciser la propriété pour l'affichage
                'choice_label' => 'name',

            ])
            // ! Can't get a way to read the property "genres" in class "App\Entity\Movie".
            ->add('genres', EntityType::class, [
                    // * c'est un ChoiceType : multiple + expanded
                    // ! Genres c'est un tableau : multiple = true
                    "multiple" => true,
                    "expanded" => true, // checkbox
                    // * EntityType : class est obligatoire
                    "class" => Genre::class,
                    // * EntityType : choice_label est obligatoire
                    'choice_label' => 'name',
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
