<?php

namespace App\Form\Back;

use App\Entity\Movie;
use App\Entity\Season;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', IntegerType::class, ["label" => new TranslatableMessage("Numéro de la saison", ["Numéro de la saison" => "Numéro de la saison"]), 
            'attr' => [
                'min' => 1
            ]])
            ->add('nbEpisodes', IntegerType::class, ["label" => "Nombre d'épisodes", 'attr' => [
                'min' => 1
            ]])
            ->add('movies', EntityType::class, [
                "multiple" => false,
                "expanded" => false, 
                "class" => Movie::class,
                'choice_label' => 'title',
                'label' => "Série",
                "query_builder" => function(EntityRepository $er){
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
