<?php

namespace App\Form;

use App\Admin\Services;
use App\Entity\Groupe;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupeType extends AbstractType
{
    public $listeLien;
    private $route;

    public function __construct(Services $listeLien,RequestStack $route,Container $container){
        $this->listeLien=$listeLien;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('titre',TextType::class,["label" => false,])
            ->add('lien',ChoiceType::class,
                [
                    'expanded'     => false,
                    'placeholder' => 'Choisir un lien',
                    'required'     => true,
                    'label'=>false,
                    /*   'attr' => ['class' => 'select2_multiple'],
                       'multiple' => true,*/
                    //'choices_as_values' => true,

                    'choices'  => array_flip($this->listeLien->listeLien()),

                ])
            ->add('icon',TextType::class,["label" => false,])
            ->add('ordre',IntegerType::class,["label" => false,])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Groupe::class,
        ]);
    }
}
