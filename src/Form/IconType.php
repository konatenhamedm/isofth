<?php

namespace App\Form;

use App\Entity\Icon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IconType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          /*  ->add('d1')
            ->add('fill')
            ->add('opacite')
            ->add('transform')
            ->add('rule')*/
            ->add('x')
            ->add('y')
            ->add('width')
            /*->add('height')*/
            ->add('exist',ChoiceType::class,
                [
                    'expanded'     => false,
                    'placeholder' => 'Choisir un type',
                    'required'     => true,
                 /*   'attr' => ['class' => 'select2_multiple'],
                    'multiple' => true,*/
                    //'choices_as_values' => true,

                    'choices'  => array_flip([
                        'rect'        => 'Rect',
                        'polygone'       => 'Polygone',
                    ]),
                ])
           /* ->add('point')
            ->add('rx')
            ->add('module')*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Icon::class,
        ]);
    }
}
