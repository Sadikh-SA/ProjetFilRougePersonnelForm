<?php

namespace App\Controller;

use App\Entity\Commission;
use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\CommissionRepository;
use App\Repository\CompteRepository;
use App\Repository\UtilisateurRepository;
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
    public function envoyerArgent(Request $request, CommissionRepository $commissionRepository, CompteRepository $compteRepository)
    {
        $values = $request->request->all();
        $montant = 'montantEnvoyer';
        $montants = 'montantEnvoyer';
        $i=0;
        $connect = $this->getDoctrine()->getManager();
        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction);
        $errors = [];
        $form->submit($values);
        if($form->isSubmitted()){
            $tester=$commissionRepository->findAll();
            while ($i<count($tester)) {
                if ($values[$montant]<=$tester[$i]->getBorneSuperieure() && $values[$montant]>=$tester[$i]->getBorneInferieure()) {
                    $montant = $tester[$i]->getValeur();
                    break;
                }
                $i++;
            }
            if (!is_numeric($montant)) {
                $errors[] = "On ne peut pas faire une transaction pour ce somme";
            }
            $transaction->setCommissionTTC($tester[$i]);
            $transaction->setType(false);
            $transaction->setUtilisateur($this->getUser());
            $transaction->setTotalEnvoyer($values[$montants]+ $montant);
            $transaction->setNumeroTransaction(rand(100000000,999999999));
            $transaction->setDateEnvoie(new \DateTime());
            $transaction->setCommissionEtat(($montant*30)/100);
            $transaction->setCommissionWari(($montant*40)/100);
            $transaction->setCommissionEnvoi(($montant*10)/100);
            $comptepartenaire = $this->getUser()->getCompte();
            if ($comptepartenaire == NULL || $comptepartenaire->getPartenaire()!=$this->getUser()->getPartenaire() || $comptepartenaire->getSolde()<= $values[$montants]+ $montant) {
                $errors[]='Vous ne pouvez pas faire de transaction car on ne vous a pas assigné de compte ou Vous êtes un Hacker ou solde insuffisant';
            }
            if ($errors) {
                return $this->json([
                    'errors' => $errors
                ], 400);
            }
            $comptepartenaire->setSolde($comptepartenaire->getSolde() - ($values[$montants]+ $montant) + (($montant*10)/100));
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
    public function retraitArgent(Request $request, CompteRepository $compteRepository,EntityManagerInterface $entityManager, Transaction $transaction=null)
    {
        $values = $request->request->all();
        
        if ($transaction==NULL) {
            $errors[] = 'cet transaction n\'existe pas dans la base' ;
        }
        if ($transaction->getType()) {
            $data = [
                'status' => 400,
                'message3' => "Le retrait de ce transaction est deja fait"
            ];
            return new JsonResponse($data,200);
        }
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->submit($values);
        $errors = [];
        if($form->isSubmitted()){
            $transaction->setUtilisateur($this->getUser());
            $transaction->setTotalEnvoyer($transaction->getTotalEnvoyer());
            $transaction->setDateRetrait(new \DateTime());
            $transaction->setType(true);
            $transaction->setMontantRetirer($transaction->getTotalEnvoyer() - $transaction->getCommissionTTC()->getValeur());
            $transaction->setCommissionRetrait(($transaction->getCommissionTTC()->getValeur()*20)/100);
            $comptepartenaire = $this->getUser()->getCompte();
            if ($comptepartenaire == NULL || $comptepartenaire->getPartenaire()!=$this->getUser()->getPartenaire()) {
                $errors[]='Vous ne pouvez pas faire de transaction car on ne vous a pas assigné de compte ou Vous êtes un Hacker';
            }
            if (!$errors) {
                $comptepartenaire->setSolde($comptepartenaire->getSolde() + ($transaction->getTotalEnvoyer() - $transaction->getCommissionTTC()->getValeur()) + ($transaction->getCommissionTTC()->getValeur()*20)/100);
                $entityManager->persist($transaction);
                $compteEtat = $compteRepository->findByNumeroCompte(1960196019604);
                $compteWari = $compteRepository->findByNumeroCompte(2019201920190);
                $compteEtat[0]->setSolde($transaction->getCommissionEtat());
                $compteWari[0]->setSolde($transaction->getCommissionWari());
                $entityManager->flush();
                $data = [
                    'status3' => 200,
                    'message3' => "Le retrait est fait avec succès.",
                    'montant retirer' => $transaction->getMontantRetirer()
                ];
                return new JsonResponse($data,200);
            } else {
                return $this->json([
                    'errors' => $errors
                ], 400);
            }
        }
    }
}
