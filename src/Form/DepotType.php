<?php

namespace App\Form;

use App\Entity\Depot;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Proxies\__CG__\App\Entity\Compte;
use App\Entity\Utilisateur;

class DepotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('dateDepot')
            ->add('montantDepot')
            ->add('compte', EntityType::class, [
                'class' => Compte::class,
                'choice_label' => 'compte'
            ])
            // ->add('caissier', EntityType::class, [
            //     'class' => Utilisateur::class,
            //     'choice_label' => 'caissier'
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Depot::class,
            'csrf_protection' =>false
        ]);
    }
}
