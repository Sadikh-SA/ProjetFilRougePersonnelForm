<?php

namespace App\Form;

use App\Entity\Transaction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            # ->add('totalEnvoyer')
            ->add('montantRetirer')
            ->add('CNIBeneficiaire')
            # ->add('dateEnvoie')
            # ->add('dateRetrait')
            ->add('type')
            # ->add('commissionEtat')
            # ->add('commissionWari')
            # ->add('commissionEnvoi')
            # ->add('commissionRetrait')
            ->add('utilisateur')
            #->add('commissionTTC')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }

    # public function recherche_par_date($datedebut, $datefin): array 
    #     $qb = $this->createQueryBuilder('transaction')
    #         ->andWhere('transaction.dateEnvoie >= :dateEnvoie')
    #         ->andWhere('transaction.dateEnvoie <= :dateEnvoie')
    #         ->setParameter('dateEnvoie', $datedebut)
    #         ->setParameter('dateEnvoie', $datefin)
    #         ->orderBy('transaction.datedebut', 'ASC')
    #         

}
