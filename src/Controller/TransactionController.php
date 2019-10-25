<?php

namespace App\Controller;

use App\Entity\Commission;
use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\CompteRepository;
use App\Repository\CommissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Message;

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
                    'message' =>'Envoie Argent fait avec succès',
                    'numerotransaction' => $transaction->getNumeroTransaction(),
                    'recu' => $transaction
                ]);
        }
        return $this->json([
            'status' => 500,
            'message0' =>'Une erreurs s\'est produite: il y\'a des champs manquantes ou ce transaction existe déja'
        ]);
    }


    /**
     * @Route("/faire/retrait", name="retrait_argent", methods={"PUT","POST"})
     * @IsGranted("ROLE_Utilisateur", message="Seul un utilisateur est habilité à effectuer une transaction")
     */
    public function retraitArgent(Request $request, CompteRepository $compteRepository,EntityManagerInterface $entityManager, Transaction $transaction=null, TransactionRepository $transactionRepository)
    {
        $values = $request->request->all();
        $transaction = $transactionRepository->findByNumeroTransaction($values['numeroTransaction']);
        
        if ($transaction==NULL) {
            $errors[] = 'cet transaction n\'existe pas dans la base' ;
        }
        if ($transaction[0]->getType()==true) {
            $data = [
                'status' => 400,
                'message3' => "Le retrait de ce transaction est deja fait"
            ];
            return new JsonResponse($data,200);
        }
        $envoyeur=$transaction[0]->getUtilisateur();
        $form = $this->createForm(TransactionType::class, $transaction[0]);
        $form->submit($values);
        $errors = [];
        
        if($form->isSubmitted()){
            $transaction[0]->setUtilisateur($envoyeur);
            $transaction[0]->setUserRetrait($this->getUser());
            $transaction[0]->setTotalEnvoyer($transaction[0]->getTotalEnvoyer());
            $transaction[0]->setDateRetrait(new \DateTime());
            $transaction[0]->setType(true);
            $transaction[0]->setMontantRetirer($transaction[0]->getTotalEnvoyer() - $transaction[0]->getCommissionTTC()->getValeur());
            $transaction[0]->setCommissionRetrait(($transaction[0]->getCommissionTTC()->getValeur()*20)/100);
            $comptepartenaire = $this->getUser()->getCompte();
            if ($comptepartenaire == NULL || $comptepartenaire->getPartenaire()!=$this->getUser()->getPartenaire()) {
                $errors[]='Vous ne pouvez pas faire de transaction car on ne vous a pas assigné de compte ou Vous êtes un Hacker';
            }
            if (!$errors) {
                $comptepartenaire->setSolde($comptepartenaire->getSolde() + ($transaction[0]->getTotalEnvoyer() - $transaction[0]->getCommissionTTC()->getValeur()) + ($transaction[0]->getCommissionTTC()->getValeur()*20)/100);
                $entityManager->persist($transaction[0]);
                $compteEtat = $compteRepository->findByNumeroCompte(1960196019604);
                $compteWari = $compteRepository->findByNumeroCompte(2019201920190);
                $compteEtat[0]->setSolde($transaction[0]->getCommissionEtat());
                $compteWari[0]->setSolde($transaction[0]->getCommissionWari());
                $entityManager->flush();
                return $this->json([
                    'status3' => 200,
                        'message3' => "Le retrait est fait avec succès.",
                        'montant retirer' => $transaction[0]->getMontantRetirer(),
                        'recu' => $transaction[0]
                    ]);
            } else {
                return $this->json([
                    'errors' => $errors
                ], 400);
            }
        }
    }


    /**
     * @Route("/lister/transaction", name="lister_transaction", methods={"POST", "GET"})
     */
    public function listertransaction(TransactionRepository $transactionRepository, SerializerInterface $serializer)
    {
       $transaction = $transactionRepository->findAll();
        $data = $serializer->serialize($transaction, 'json', [ 'groups' => 'transaction']);
        return new Response(
           $data,200,[
               'Content-Type' => 'application/json'
           ]
        );

    }



    /**
     * @Route("/lister/transaction/date", name="transaction_date", methods={"POST","GET"})
     */
    public function transaction(Request $request, TransactionRepository $transactionRepository) : Response 
    {
        $values = json_decode($request->getContent());
        $transaction = $transactionRepository->findAll();
        $date = new \DateTime($values->datefin);
        $datefin = $transactionRepository->findByDateEnvoie($date);
        $datebut = $transactionRepository->findByDateEnvoie($values['datedebut']);
        if ($transaction->getDateEnvoie()<=$datefin && $transaction->getDateEnvoie()>=$datebut) {
            $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);
            $jsonObject = $serializer->serialize($transaction, 'json', [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]);
            return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
        }
        else {
            return $this->json([
                'Message' => 'Pas de Resultat pour ce recherche'
            ]);
        }
    }


    /**
     * @Route("/select/transaction", name="lister_un_transaction_quelconque", methods={"POST","GET"})
     */
    public function selectTransaction(Request $request, TransactionRepository $TransactionRepository, SerializerInterface $serializer)
    {
        $values = $request->request->all();
        $transaction = $TransactionRepository->findByNumeroTransaction($values['numeroTransaction']);
        $data = $serializer->serialize($transaction, 'json');
        return new Response(
           $data,200,[
               'Content-Type' => 'application/json'
           ]
        );

    }


    /**
     *@Route("/select/transaction/partenaire", name="lister_les_transactions_d_un_partenaire_quelconque", methods={"POST","GET"})
     *@IsGranted("ROLE_Utilisateur", message="Seul un utilisateur est habilité à effectuer une transaction")
     */
    /*public function selectTransactionPartenaire(Request $request, TransactionRepository $TransactionRepository, SerializerInterface $serializer)
    {
        $values = $request->request->all();
        $transaction = new Transaction();
        $transactions = $TransactionRepository->findAll();
        $data = $serializer->serialize($transaction, 'json');
    
        return new Response(
           $data,200,[
               'Content-Type' => 'application/json'
           ]
        );

    }*/

    /**
     *@Route("/select/transaction/user", name="lister_les transactions_d_un_partenaire1_quelconque", methods={"POST","GET"})
     *@IsGranted({"ROLE_Partenaire", "ROLE_Admin-Partenaire", "ROLE_Utilisateur"}, message="Seul un utilisateur est habilité à effectuer une transaction")
     */
    public function selectTransacPartenaire(Request $request, TransactionRepository $TransactionRepository, UtilisateurRepository $UtilisateurRepository , SerializerInterface $serializer)
    {
        $usertransaction = $this->getUser();
        $resultat = $TransactionRepository->transactionUserMoimeme($usertransaction);
        $data = $serializer->serialize($resultat, 'json');
    
        return new Response(
           $data,200,[
               'Content-Type' => 'application/json'
           ]
        );

    }

    /**
     *@Route("/select/transaction/user", name="lister_les transactions_d_un_partenaire1_quelconque", methods={"POST","GET"})
     *@IsGranted({"ROLE_Partenaire", "ROLE_Admin-Partenaire", "ROLE_Utilisateur"}, message="Seul un utilisateur est habilité à effectuer une transaction")
     */
    public function selectTransacDate(Request $request, TransactionRepository $TransactionRepository, UtilisateurRepository $UtilisateurRepository , SerializerInterface $serializer)
    {
        $values = $request->request->all();

        $resultat = $TransactionRepository->transactionParDate($values['dateEnvoie'],$values['dateRetrait']);
        
        $data = $serializer->serialize($resultat, 'json');
    
        return new Response(
           $data,200,[
               'Content-Type' => 'application/json'
           ]
        );

    }
}
