<?php

namespace App\Controller;

use App\Entity\Commission;
use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\CommissionRepository;
use App\Repository\UtilisateurRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/filrouge")
 */
class TransactionController extends AbstractController
{
    
    /**
     * @Route("/faire/envoie", name="envoyer_argent", methods={"POST"})
     * @IsGranted("ROLE_Utilisateur", message="Seul un utilisateur est habilité à effectuer une transaction")
     */
    public function envoyerArgent(Request $request, CommissionRepository $commissionRepository)
    {
        $values = $request->request->all();
        $montant = 'montantEnvoyer';
        $i=0;
        $connect = $this->getDoctrine()->getManager();
        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->submit($values);
        if($form->isSubmitted()){
            $tester=$commissionRepository->findAll();
            while ($i<count($tester)) {
                if ($values[$montant]<=$tester[$i]->getBorneSuperieure() && $values[$montant]>=$tester[$i]->getBorneInferieure()) {
                    $montant = $tester[$i]->getValeur();
                    break;
                }
                var_dump($tester[$i]);
                $i++;
            }
            var_dump($tester[1]);die();
            $transaction->setCommissionTTC($montant);
            $transaction->setUtilisateur($this->getUser());
            $transaction->setTotalEnvoyer($values['montantEnvoyer']+$values['commissionTTC']);
            $transaction->setNumeroTransaction(rand(100000000,999999999));
            $transaction->setDateEnvoie(new \DateTime());
            $connect->persist($transaction);
            $connect->flush();
                return $this->json([
                    'code' => 200,
                    'message' =>'Envoie Argent fait avec succès'
                ]);
        }
        return $this->json([
            'status' => 500,
            'message0' =>'Une erreurs s\'est produite: il y\'a des champs manquantes ou ce transaction existe déja'
        ]);
    }


    /**
     * @Route("/faire/retrait/{id}", name="retrait_argent", methods={"PUT","POST"})
     * @IsGranted("ROLE_Utilisateur", message="Seul un utilisateur est habilité à effectuer une transaction")
     */
    public function retraitArgent(Request $request, CommissionRepository $commissionRepository,EntityManagerInterface $entityManager, Transaction $transaction=null)
    {
        $values = $request->request->all();
        
        if ($transaction==NULL) {
            $data = [
                'status2' => 404,
                'message2' => 'cet transaction n\'existe pas dans la base' 
            ];
            return new JsonResponse($data,404);
        }
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->submit($values);
        if($form->isSubmitted()){
            $transaction->setUtilisateur($this->getUser());
            $transaction->setTotalEnvoyer($transaction->getTotalEnvoyer());
            $transaction->setDateRetrait(new \DateTime());
            $transaction->setMontantRetirer($transaction->getTotalEnvoyer() - $transaction->getCommissionTTC()->getValeur());
            $entityManager->persist($transaction);
            $entityManager->flush();
            $data = [
                'status3' => 200,
                'message3' => "Le retrait est fait avec succès.",
                'montant retirer' => $transaction->getMontantRetirer()
            ];
            return new JsonResponse($data,200);
        }
    }

}
