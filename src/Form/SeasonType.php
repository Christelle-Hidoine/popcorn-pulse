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
                // 'query_builder' => function (EntityRepository $er) {
                //     return $er->createQueryBuilder('movie','type')
                //         ->select("movie", "type")
                //         ->from('App\Entity\Movie', 'movie')
                //         ->join('type.movies', 'type')
                //         ->where('type.name LIKE :name')
                //         ->setParameter('name', "%Série%" );
                //     },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
