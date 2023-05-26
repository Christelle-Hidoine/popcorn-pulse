<?php

namespace App\Form\Front;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   
        
        $icons = [
            "😂 Rire" => 'smile',
            "😭 Pleurer" => 'cry',
            "😴  Dormir" => 'sleep',
            "🤔 Réfléchir" => 'think',
            "🤩 Rêver" => 'dream', 
        ];
       

        $builder
            ->add('username', TextType::class, [
                "label" => new TranslatableMessage("Pseudonyme", ["Pseudonyme" => "Pseudonyme"]),
                "attr" => [
                    "placeholder" => new TranslatableMessage("Votre pseudo", ["Votre pseudo" => "Votre Pseudo"])
                    ]
                ])
            ->add('email', EmailType::class, [
                "label" => new TranslatableMessage("Email", ["Email" => "Email"]), 
                "attr" => [
                    "placeholder" => new TranslatableMessage("exemple@cinema.com", ["exemple@cinema.com" => "exemple@cinema.com"])
                    ]
                ])
            ->add('content', TextareaType::class, ["label" => new TranslatableMessage("Critique", ["Critique" => "Critique"]), 
                "attr" => [
                    "placeholder" => new TranslatableMessage("écrivez votre critique ici, et ne soyez pas trop méchant 😉", [
                        "écrivez votre critique ici, et ne soyez pas trop méchant 😉" => "écrivez votre critique ici, et ne soyez pas trop méchant 😉"]
                        )
                    ]
                ])
            ->add('rating', ChoiceType::class, [
                'choices' => [
                    'Excellent' => 5,
                    'Très bon' => 4,
                    'Bon' => 3,
                    'Peut mieux faire' => 2,
                    'A éviter' => 1
                ],
                "expanded" => true,
                "multiple" => false,
                "label" => new TranslatableMessage("Avis", ["Avis" => "Avis"]), 
                "help"  => new TranslatableMessage("(un seul choix possible)", ["(un seul choix possible)" => "(un seul choix possible)"])
                ])
            ->add('reactions', ChoiceType::class, [ 
                "choices" => [
                    $icons
                    ],
                "expanded" => true,
                "multiple" => true,
                "label" => new TranslatableMessage("Ce film vous a fait : ", ["Ce film vous a fait : " => "Ce film vous a fait : "]), 
                "help" => new TranslatableMessage("(plusieurs choix possibles)", ["(plusieurs choix possibles)" => "(plusieurs choix possibles)"])
                ])
            ->add('watchedAt', DateType::class, ['widget' => 'single_text', 'input' => 'datetime_immutable', 
                "label" => new TranslatableMessage("Vous avez vu ce film le : ",["Vous avez vu ce film le : " => "Vous avez vu ce film le : "])
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
