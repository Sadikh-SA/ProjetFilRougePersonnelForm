<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CompteType;
use App\Entity\Compte;
use App\Entity\Partenaire;
use App\Repository\PartenaireRepository;
use App\Form\PartenaireType;
use App\Entity\Depot;
use App\Form\DepotType;
use App\Repository\CompteRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/filrouge")
 */
class CompteDepotController extends AbstractFOSRestController
{
    /**
     * @Route("/compte/ajouter", name="compte_ajout", methods={"POST"})
     */
    public function creercompte(Request $request, PartenaireRepository $partenaireRepository)
    {
        $values = $request->request->all();
        $connect = $this->getDoctrine()->getManager();
        $compte = new Compte();
        $form = $this->createForm(CompteType::class, $compte);
        $form->submit($values);
        $compte->setNumeroCompte(rand(1000000000000,10000000000000));
        $compte->setSolde(0);
        $partenaire = $partenaireRepository->find($values['partenaire']);
        $compte->setPartenaire($partenaire);
        $compte->setDateCreation(new \DateTime());
        $connect->persist($compte);
        $connect->flush();
        return $this->json([
            'code' => 200,
            'message' =>'Nouveau Compte Ajouté'
        ]);
    }


    /**
     * @Route("/partenaire/ajouter", name="compte_ajout", methods={"POST"})
     */
    public function creerPartenaire(Request $request)
    {
        $values = $request->request->all();
        $connect = $this->getDoctrine()->getManager();
        $partenaire = new Partenaire();
        $form= $this->createForm(PartenaireType::class, $partenaire);
        $form->submit($values);
        if($form->isSubmitted()){
            $partenaire->setDateCreation(new \DateTime());
            $partenaire->setStatut(true);
            $connect->persist($partenaire);
            $connect->flush();
                return $this->json([
                    'code' => 200,
                    'message' =>'Nouveau Partenaire Ajouté'
                ]);
        }
        return $this->json([
            'status' => 500,
            'message0' =>'Une erreurs s\'est produite: il y\'a des champs manquantes ou ce ninea existe déja'
        ]);

    }


    /**
     * @Route("/fairedepot", name="faire_depot", methods={"POST"})
     * @IsGranted("ROLE_Caissier", message="Seul le caissier est habilité à effectuer cette transaction")
     */
    public function fairedepot(Request $request, CompteRepository $compteRepository)
    {
        $values = $request->request->all();
        $depot = new Depot();
        $connect = $this->getDoctrine()->getManager();
        $form = $this->createForm(DepotType::class, $depot);
        $form->submit($values);
        $user= $this->getUser();
        if($form->isSubmitted()){
            $depot->setDateDepot(new \DateTime());
            $depot->setCaissier($user);
            $compte = $compteRepository->find($values['compte']);
            if ($compte==NULL) {
                return $this->json([
                    'message1' =>'Ce compte n\'existe pas'
                ]);
            }
            $compte->setSolde($compte->getSolde()+$values['montantDepot']);
            $connect->persist($compte);
            $depot->setCompte($compte);
            $connect->persist($depot);
            $connect->flush();
            return $this->json([
                'code' => 200,
                'message1' =>'Nouveau Dépot Ajouté'
            ]);
        }
        return $this->json([
            'status' => 500,
            'message0' =>'Une erreurs s\'est produite: il y\'a des champs manquantes'
        ]);

    }
}
