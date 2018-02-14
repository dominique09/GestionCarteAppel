<?php
namespace App\Form;

use App\Entity\Intervenant;
use App\Entity\Role;
use App\Entity\User;
use App\Repository\IntervenantRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                'label' => "Nomd'utilisateur",
                'disabled' => true
            ))
            ->add('email', EmailType::class, array('label' => 'Adresse Courriel'))
            ->add('active', CheckboxType::class, array(
                'required' => false,
                'label' => 'Usager Actif'
            ))
            ->add('roles', EntityType::class, array(
                'class' => Role::class,
                'choice_label' => function(Role $role){
                    return $role->getName() . " [". $role->getRole() ."]";
                },
                'multiple' => true,
                'expanded' => true
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Modifier',
                'attr' => array('class' =>'btn btn-success pull-right')))
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}