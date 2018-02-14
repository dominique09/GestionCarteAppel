<?php
/**
 * Created by PhpStorm.
 * User: dseptembre
 * Date: 18-02-08
 * Time: 19:56
 */

namespace App\Form;


use App\Entity\Division;
use App\Entity\Formation;
use App\Entity\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Nom'))
            ->add('role', TextType::class, array('label' => 'Role'))
            ->add('submit', SubmitType::class, array(
                'label' => 'Envoyer',
                'attr' => ['class' => 'btn btn-success pull-right']
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Role::class,
        ]);
    }
}