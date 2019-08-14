<?php

namespace App\Form;

use App\Entity\Commission;
use App\Entity\Transaction;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomEnvoyeur')
            ->add('prenomEnvoyeur')
            ->add('adresseEnvoyeur')
            ->add('telEnvoyeur')
            ->add('CNIEnvoyeur')
            ->add('nomBeneficiaire')
            ->add('prenomBeneficiaire')
            ->add('telBeneficiaire')
            ->add('adresseBeneficiaire')
            ->add('numeroTransaction')
            ->add('montantEnvoyer')
            //->add('totalEnvoyer')
            ->add('montantRetirer')
            ->add('CNIBeneficiaire')
            // ->add('dateEnvoie', DateType::class,[
            //     'widget' => 'single_text',
            //     'format' => 'yyyy-mm-dd'
            // ])
            ->add('dateRetrait')
            // ->add('utilisateur', EntityType::class,[
            //     'class' => Utilisateur::class,
            //     'choice_label' => 'utilisateur'
            // ])
            // ->add('commissionTTC', EntityType::class,[
            //     'class' => Commission::class,
            //     'choice_label' => 'commissionTTC'
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
