<?php

namespace App\Form;

use App\Entity\Compte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Partenaire;

class CompteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('numeroCompte')
            ->add('codeBank')
            ->add('nomBeneficiaire')
            // ->add('solde')
            // ->add('dateCreation')
            ->add('Partenaire', EntityType::class,[
                'class' => Partenaire::class,
                'choice_label' => 'partenaire'
             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Compte::class,
            'csrf_protection' =>false
        ]);
    }
}
