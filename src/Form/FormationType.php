<?php
/**
 * Created by PhpStorm.
 * User: dseptembre
 * Date: 18-02-08
 * Time: 19:56
 */

namespace App\Form;


use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array('label' => 'Nom'))
            ->add('abreviation', TextType::class, array('label' => 'Abréviation'))
            ->add('color', ChoiceType::class, array(
                'label' => "Couleur",
                'choices' => [
                    'secondary' => 'secondary',
                    'primary' => 'primary',
                    'success' => 'success',
                    'danger' => 'danger',
                    'warning' => 'warning',
                    'info' => 'info',
                    'light' => 'light',
                    'dark' => 'dark'
                ],
                'choice_attr' => function($val, $key, $index){
                    return ['class' => 'bg-'.$key];
                }
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Envoyer',
                'attr' => ['class' => 'btn btn-success pull-right']
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}