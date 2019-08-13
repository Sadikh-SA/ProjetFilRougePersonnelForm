<?php

namespace App\Form;

use App\Entity\Compte;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Proxies\__CG__\App\Entity\Partenaire;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('password')
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('email')
            ->add('tel')
            ->add('profil')
            // ->add('dateCreation', DateType::class,[
            //     'widget' => 'single_text',
            //     'format' => 'yyyy-mm-dd'
            // ])
            ->add('partenaire',EntityType::class,[
                'class' => Partenaire::class,
                'choice_label' => 'partenaire'
            ])
             ->add('compte',EntityType::class,[
                'class' => Compte::class,
                'choice_label' => 'compte'
             ])
             ->add('imageFile', VichFileType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
            'csrf_protection' =>false
        ]);
    }
}
