<?php

namespace App\Form;

use App\Entity\Abonnement;
use App\Entity\Client;
use App\Entity\ModuleParent;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbonnementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('dateDebut',DateType::Class, [
                "required" => false,
                "widget" => 'single_text',
                "input_format"=>'Y-m-d',
                "by_reference" => true,
                "empty_data" => '',
                'label'=>"Date debut",
            ])
            ->add('dateFin',DateType::Class, [
                "required" => false,
                "widget" => 'single_text',
                "input_format"=>'Y-m-d',
                "by_reference" => true,
                "empty_data" => '',
                'label'=>"Date fin",
            ])
            ->add('etat',ChoiceType::class,
                [
                    'expanded'     => false,
                    'placeholder' => 'Choisir un etat',
                    'required'     => true,
                   // 'attr' => ['class' => 'select2_multiple'],
                    'multiple' => false,
                    //'choices_as_values' => true,

                    'choices'  => array_flip([
                        'FIN'        => 'Fin abonnement',
                        'RENOUVELLER'       => 'Renouvellement',

                    ]),
                ])
            ->add('dateRenouvellement',DateType::Class, [
                "required" => false,
                "widget" => 'single_text',
                "input_format"=>'Y-m-d',
                "by_reference" => true,
                "empty_data" => '',
                'label'=>"Date renouvellement",
            ])
           ->add('client',EntityType::class,[
               'class' => Client::class,
               'query_builder' => function (EntityRepository $er) {
                   return $er->createQueryBuilder('u')
                       ->orderBy('u.id', 'DESC');
               },
               'choice_label' => 'nom'.' '.'prenom',

           ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonnement::class,
        ]);
    }
}
