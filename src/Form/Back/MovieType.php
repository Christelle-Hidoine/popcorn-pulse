<?php

namespace App\Form\Back;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ["label" => new TranslatableMessage("Titre", ["Titre" => "Titre"]), "attr" => ["placeholder" => "titre du film ou de la série"]])
            ->add('duration', IntegerType::class, ["label" => "Durée", "help" => "(la durée doit être indiquée en minutes)", 'attr' => [
                'min' => 1
            ]])
            ->add('rating', ChoiceType::class, [
                'choices'  => [
                'Excellent' => 5,
                'Très bon' => 4,
                'Bon' => 3,
                'Peut mieux faire' => 2,
                'A éviter' => 1],
                "expanded" => true,
                "multiple" => false,
                "label" => "Avis", "help"  => "(un seul choix possible)"])
            ->add('summary', TextareaType::class, ["label" => "Résumé", 
            "attr" => [
                "placeholder" => "écrivez un résumé du film ou de la série"]
            ])
            ->add('synopsis', TextareaType::class, ["label" => "Synopsis", 
            "attr" => [
                "placeholder" => "écrivez le synopsis du film ou de la série"]
            ])
            ->add('releaseDate', DateType::class, ['widget' => 'single_text', "label" => "Date de sortie"])
            ->add('country', CountryType::class, ["choices" => [
                "expanded" => true, 
                "multiple" => false],
                "label" => "Pays"
            ])
            ->add('poster', UrlType::class, ["label" => "Url de l'image", "attr" => ["placeholder" => "http://..."]])
            // j'ai une relation avec une autre Entité
            // l'élément HTML désiré : Choix : ChoiceType
            // on veux un choiceType spécialisé pour les entités : EntityType
            // ? https://symfony.com/doc/5.4/reference/forms/types/entity.html
            ->add('type', EntityType::class, [
                // * c'est un ChoiceType : multiple + expanded
                "multiple" => false,
                "expanded" => false, // radiobutton
                "class" => Type::class,
                // on doit préciser la propriété pour l'affichage
                'choice_label' => 'name',

            ])
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
