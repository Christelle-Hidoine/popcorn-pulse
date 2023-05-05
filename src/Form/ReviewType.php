<?php

namespace App\Form;

use App\Entity\Movie;
use App\Entity\Review;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   
        
        $builder
            ->add('username', TextType::class, ["label" => "Pseudonyme"], ["attr" => ['placeholder' => 'Votre pseudo']])
            ->add('email', EmailType::class, ["label" => "E-mail"], ["attr" => ["placeholder" => "exemple@cinema.com"]] )
            ->add('content', TextareaType::class, ["label" => "Critique"], ["attr" => ["placeholder" => "écrivez votre critique ici, et ne soyez pas trop méchant ;)"]])
            ->add('rating', ChoiceType::class, ["help" => "(un seul choix possible)"], ["label" => "Avis de 5 à 1"], ['choices'  => [
                'Excellent' => 5,
                'Très bon' => 4,
                'Bon' => 3,
                'Peut mieux faire' => 2,
                'A éviter' => 1]])
            // ->add('reactions', ChoiceType::class, ["label" => "Ce film vous a fait : "], [
            //     "multiple" => true, 
            //     "choices" => [
            //     "Rire" => "Rire",
            //     "Pleurer" => "Pleurer",
            //     "Dormir" => "Dormir" ,
            //     "Réfléchir" => "Réfléchir",
            //     "Rêver" => "Rêver"],
            //     "expanded" => true
            //     ])
            ->add('watchedAt', DateType::class, ["label" => "Vous avez vu ce film le : "], [
                'widget' => 'choice',
                'input' => 'datetime_immutable'
            ])
            ->add('movie', EntityType::class, ["class" => Movie::class, "choice_label" => 'title'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
