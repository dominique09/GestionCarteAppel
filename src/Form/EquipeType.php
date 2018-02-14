<?php
/**
 * Created by PhpStorm.
 * User: dseptembre
 * Date: 18-02-08
 * Time: 19:56
 */

namespace App\Form;


use App\Entity\Division;
use App\Entity\Equipe;
use App\Entity\Formation;
use App\Entity\Intervenant;
use App\Entity\TypeEquipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('identifiant', TextType::class)
            ->add('typeEquipe', EntityType::class, array(
                'label' => "Type d'équipe",
                'class' => TypeEquipe::class,
                'choice_label' => function(TypeEquipe $typeEquipe){
                    return $typeEquipe->getName();
                }
            ))
            ->add('intervenants', EntityType::class, array(
                'class' => Intervenant::class,
                'choice_label' => function(Intervenant $intervenant){
                    return $intervenant->getFirstname() .
                        " " .
                        $intervenant->getLastname() .
                        " - (". $intervenant->getFormation()->getNom() .
                        ")";
                },
                'choice_attr' => function(Intervenant $intervenant){
                    return ['disabled' => $intervenant->isAssigned()];
                },
                'expanded' => true,
                'multiple' => true
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
            'data_class' => Equipe::class,
        ]);
    }
}