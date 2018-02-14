<?php

namespace App\Form;

use App\Entity\Division;
use App\Entity\Formation;
use App\Entity\Intervenant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntervenantCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, array('label' => 'Prénom'))
            ->add('lastname', TextType::class, array('label' => 'Nom'))
            ->add('formation', EntityType::class, array(
                'class' => Formation::class,
                'choice_label' => function(Formation $div){
                    return $div->getNom() . " - " . $div->getAbreviation();
                },
                'expanded' => false
            ))
            ->add('division', EntityType::class, array(
                'class' => Division::class,
                'choice_label' => function(Division $div){
                    return $div->getNom() . " - " . $div->getAbreviation();
                },
                'expanded' => false
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Ajouter',
                'attr' => ['class' => 'btn btn-success pull-right']
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Intervenant::class,
        ]);
    }
}
