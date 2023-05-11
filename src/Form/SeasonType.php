<?php

namespace App\Form;

use App\Entity\Movie;
use App\Entity\Season;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', IntegerType::class, ["label" => "Numéro de la saison", 'attr' => [
                'min' => 1
            ]])
            ->add('nbEpisodes', IntegerType::class, ["label" => "Nombre d'épisodes", 'attr' => [
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
                'label' => "Série",
                // ? https://symfony.com/doc/5.4/reference/forms/types/entity.html#query-builder
                "query_builder" => function(EntityRepository $er){
                    // TODO : requete perso : tri par titre
                    // TODO : requete perso : que le type série
                    return $er->createQueryBuilder('m')
                        ->join("m.type", "t")
                        ->where("t.name = 'série'")
                        ->orderBy('m.title', 'ASC');
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}