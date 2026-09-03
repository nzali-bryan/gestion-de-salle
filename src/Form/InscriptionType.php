<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints as Assert;


class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\Email([
                        'message' => 'Veuillez entrer une adresse email valide.'
                    ]),
                ],
            ])
            ->add('roles',ChoiceType::class, [
                    'required' => false,
                    'choices' => [
                        "Administrateur de la salle" => 'ROLE_ADMIN',
                        'employés' => 'ROLE_USER',
                    ],
                'expanded' => false,
                'multiple' => true,
                    ])
            ->add('password', PasswordType::class,[
                /*
                 *pour  un mot de passe plus fort 
                */ 
                // 'constraints'=>[
                //      new Assert\PasswordStrength([
                // 'minScore' => Assert\PasswordStrength::STRENGTH_STRONG,
                //     'message' => 'Le mot de passe doit être suffisamment fort.'
                //      ]),
                // ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
