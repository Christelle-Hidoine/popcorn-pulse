<?php

namespace App\Form;

use App\Entity\Movie;
use App\Entity\Review;
use App\Repository\MovieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   

        
        // emojis pour "reactions"
        $icons = [
            "😂" => 'Rires',
            "😭" => 'Pleurer',
            "😴" => 'Dormir',
            "🤔" => 'Réfléchir',
            "🤩" => 'Rêver',
            
        ];
       
        $builder
            ->add('username', TextType::class, ["label" => "Pseudonyme"])
            ->add('email', EmailType::class, 
                // ["label" => "E-mail"], 
                ["attr" => ["placeholder" => "exemple@cinema.com"]])
            ->add('content', TextareaType::class, ["label" => "Critique"], ["attr" => ["placeholder" => "écrivez votre critique ici, et ne soyez pas trop méchant ;)"]])
            ->add('rating', ChoiceType::class, 
                ['choices'  => [
                    'Excellent' => 5,
                    'Très bon' => 4,
                    'Bon' => 3,
                    'Peut mieux faire' => 2,
                    'A éviter' => 1],
                    "label" => "Avis", "help"  => "(un seul choix possible)"])
            ->add('reactions', ChoiceType::class, [ 
                "choices" => [
                $icons], 
                // "Rires" => "Rires,
                // "Pleurer" => "Pleurer",
                // "Dormir" => "Dormir" ,
                // "Réfléchir" => "Réfléchir",
                // "Rêver" => "Rêver"],
                "expanded" => true,
                "multiple" => true,
                "label" => "Ce film vous a fait : Rire, Pleurer, Réfléchir, Dormir, Rêver ?", "help" => "(plusieurs choix possibles)"
                ])
            ->add('watchedAt', null, ["label" => "Vous avez vu ce film le : "])
            // ->add('movie', EntityType::class, ["class" => Movie::class, "choice_label" => 'title'])
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
