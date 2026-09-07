<?php

namespace App\Form;

use App\Entity\Console;
use App\Entity\Journee;
use App\Entity\SessionsJeu;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('duree',ChoiceType::class,[
                'choices'=> [
                    '15minutes' => 15, 
                    '30 minutes' => 30,
                    '1 heure' => 60,
                    '2 heures' => 120,
                    '3 heures' => 180,   
                    '4 heures' => 240,              
                ],
                'data' => 60, // un peu comme selected
            ])
            ->add('montant')
            ->add('Console', EntityType::class, [
                'class' => Console::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SessionsJeu::class,
        ]);
    }
}
