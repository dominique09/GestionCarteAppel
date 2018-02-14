<?php
/**
 * Created by PhpStorm.
 * User: dseptembre
 * Date: 18-02-08
 * Time: 19:56
 */

namespace App\Form;

use App\Entity\Appelant;
use App\Entity\Carte;
use App\Entity\Equipe;
use App\Entity\Intervenant;
use App\Entity\TypeEquipe;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarteCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('emplacement', TextType::class)
            ->add('appelant', EntityType::class, array(
                'label' => "Appelant",
                'class' => Appelant::class,
                'choice_label' => function(Appelant $appelant){
                    return $appelant->getName();
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->where('a.active = true');
                }
            ))
            ->add('clawson', TextType::class, array(
                'label' => "Code Clawson"
            ))
            ->add('priorite', IntegerType::class, array(
                'label' => "Priorité"
            ))
            ->add('description', TextareaType::class, array(
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
            'data_class' => Carte::class,
        ]);
    }
}