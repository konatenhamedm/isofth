<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('email')
           ->add('plainPassword', RepeatedType::class, [
               'mapped'=>false,
               'required'=>false,
               'type' => PasswordType::class,
               'invalid_message' => 'Les champs de mot de passe doivent correspondre.',
               'required' => true,
               'first_options'  => ['label' => 'Password'],
               'second_options' => ['label' => 'Confirm Password'],
               'constraints' => [
                   new NotBlank([
                       'message' => 'Veuillez entrer un mot de passe',
                   ]),
                   new Length([
                       'min' => 6,
                       'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                       // max length allowed by Symfony for security reasons
                       'max' => 4096,
                   ]),
               ]
           ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
