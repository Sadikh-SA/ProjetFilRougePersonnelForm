<?php

namespace App\Controller;

use App\Entity\Depot;
use App\Entity\Compte;
use App\Form\DepotType;
use App\Form\CompteType;
use App\Entity\Partenaire;
use App\Entity\Utilisateur;
use App\Form\PartenaireType;
use App\Form\UtilisateurType;
use App\Repository\CompteRepository;
use App\Repository\DepotRepository;
use App\Repository\ProfilRepository;
use App\Repository\PartenaireRepository;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/filrouge")
 */
class CompteDepotController extends AbstractFOSRestController
{


    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder )
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    

    /**
     * @Route("/compte/ajouter", name="compte_ajout", methods={"POST"})
     * @IsGranted({"ROLE_Wari","ROLE_Super-Admin"}, message="Vous ne pouvez pas créer un compte: pas d'accès")
     */
    public function creerCompte(Request $request, PartenaireRepository $partenaireRepository, ValidatorInterface $validator)
    {
        $values = $request->request->all();
        $connect = $this->getDoctrine()->getManager();
        $compte = new Compte();
        $form = $this->createForm(CompteType::class, $compte);
        $form->submit($values);
        if ($form->isSubmitted()) {
            $compte->setNumeroCompte(rand(1000000000000,9999999999999));
            $compte->setSolde(0);
            $partenaire = $partenaireRepository->findByNinea($values['partenaire']);
            if ($partenaire==NULL || $partenaire[0]==NULL) {
                return $this->json([
                    'code' => 300,
                    'Description' =>'Il faut un partenaire pour ce compte ou ce partenaire n\'existe pas'
                ]);
            }
            $compte->setPartenaire($partenaire[0]);
            $compte->setDateCreation(new \DateTime());
            $connect->persist($compte);
            $connect->flush();
            return $this->json([
                'code' => 200,
                'message' =>'Nouveau Compte Ajouté'
            ]);
        }
        return $this->handleView($this->view($validator->validate($form)));
    }


    /**
     * @Route("/partenaire/ajouter", name="compte_ajouter", methods={"POST"})
     * @IsGranted({"ROLE_Wari","ROLE_Super-Admin"}, message="Vous ne pouvez pas créer un Partenaire: pas d'accès")
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
     * @IsGranted("ROLE_Caissier", message="Seul le caissier est habilité à effectuer cet Dépot")
     */
    public function faireDepot(Request $request, CompteRepository $compteRepository)
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
            $compte = $compteRepository->findByNumeroCompte($values['compte']);
            if ($compte==NULL) {
                return $this->json([
                    'message1' =>'Ce compte n\'existe pas'
                ]);
            }
            $compte[0]->setSolde($compte[0]->getSolde()+$values['montantDepot']);
            $connect->persist($compte[0]);
            $depot->setCompte($compte[0]);
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


    /**
     * @Route("/update/user/{id}", name="modifier_user", methods={"PUT","POST"})
     * @IsGranted({"ROLE_Wari", "ROLE_Admin-Partenaire", "ROLE_Partenaire", "ROLE_Super-Admin"}, message="Vous n'avez pas les droits pour modifier un utilisateur")
     */
    public function Update(Request $request, ProfilRepository $profilRepository , EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, Utilisateur $user=null)
    {
        $values = $request->request->all();
        if ($user==NULL) {
            $data = [
                'status2' => 404,
                'message2' => 'cet utilisateur n\'existe pas dans la base' 
            ];
            return new JsonResponse($data,404);
        }
        $form = $this->createForm(UtilisateurType::class, $user);
        $errors = [];
        $form->submit($values);
        $statut = "statut";
        if($form->isSubmitted()){
            $user->setPassword($passwordEncoder->encodePassword($user, trim($values['password'])));
            if (isset($values[$statut])) {
                $user->setStatut($values[$statut]);
            }
            $profil = $profilRepository->find($values['profil']);
            if (!$profil) {
                $errors[] = "Ce Profil n'existe pas";
            }elseif ($profil->getLibelle()=="Admin-Partenaire") {
                $user->setRoles(["ROLE_Admin-Partenaire"]);
            }
            elseif ($profil->getLibelle()=="Super-Admin") {
                $user->setRoles(["ROLE_Super-Admin"]);
            }elseif ($profil->getLibelle()=="Caissier") {
                $user->setRoles(["ROLE_Caissier"]);
            }elseif ($profil->getLibelle()=="Utilisateur") {
                $user->setRoles(["ROLE_Utilisateur"]);
            }
            elseif ($profil->getLibelle()=="Partenaire") {
                $user->setRoles(["ROLE_Partenaire"]);
            }elseif ($profil->getLibelle()=="Wari") {
                $errors[]= "Impossible de changer ce statut";
            }
            
            if ($errors) {
                return $this->json([
                    "errors" => $errors
                ]);
            } else {
                $entityManager->persist($user);
                $entityManager->flush();
                $data = [
                    'status3' => 200,
                    'message3' => "Le mise à jour de l'Utilisateur est bien fait."
                ];
                return new JsonResponse($data,200);
            }
            
        }
        
    }


    /**
     * @Route("/update/compte/{id}", name="modifier_compte", methods={"PUT","POST"})
     * @IsGranted({"ROLE_Wari", "ROLE_Super-Admin"}, message="Vous n'avez pas les droits pour modifier un Compte")
     */
    public function UpdateCompte(Request $request, EntityManagerInterface $entityManager, Compte $compte=null)
    {
        $values = $request->request->all();
        if ($compte==NULL) {
            $data = [
                'status10' => 404,
                'message10' => 'cet compte n\'existe pas dans la base' 
            ];
            return new JsonResponse($data,404);
        }
        $form = $this->createForm(CompteType::class, $compte);
        $form->submit($values);

        if($form->isSubmitted()){
            $entityManager->persist($compte);
            $entityManager->flush();
            $data = [
                'status12' => 200,
                'message12' => "Le mise à jour du compte a réussi."
            ];
            return new JsonResponse($data,200);
        }
            $data = [
                'status13' => 500,
                'message13' => 'Erreur'
            ];
            return new JsonResponse($data,500);
        
    }


    /**
     * @Route("/update/depot/{id}", name="modifier_depot", methods={"PUT","POST"})
     * @IsGranted("ROLE_Caissier", message="Vous n'avez pas les droits pour modifier un dépot")
     */
    public function UpdateDepot(Request $request, EntityManagerInterface $entityManager, Depot $depot=null)
    {
        $values = $request->request->all();
        if ($depot==NULL) {
            $data = [
                'status10' => 404,
                'message10' => 'ce dépôt n\'existe pas dans la base' 
            ];
            return new JsonResponse($data,404);
        }
        $form = $this->createForm(DepotType::class, $depot);
        $form->submit($values);

        if($form->isSubmitted()){
            $entityManager->persist($depot);
            $entityManager->flush();
            $data = [
                'status12' => 200,
                'message12' => "Le mise à jour du dépôt a réussi."
            ];
            return new JsonResponse($data,200);
        }
            $data = [
                'status13' => 500,
                'message13' => 'Erreur'
            ];
            return new JsonResponse($data,500);
        
    }



    /**
     * @Route("/update/partenaire/{id}", name="modifier_partenaire", methods={"PUT","POST"})
     * @IsGranted({"ROLE_Wari", "ROLE_Admin-Partenaire", "ROLE_Partenaire", "ROLE_Super-Admin"}, message="Vous n'avez pas les droits pour créer un new utilisateur")
     */
    public function UpdatePartenaire(Request $request, EntityManagerInterface $entityManager, Partenaire $partenaire=null)
    {
        $values = $request->request->all();
        $statut="statut";
        if ($partenaire==NULL) {
            $data = [
                'status5' => 404,
                'message5' => 'cet Partenaire n\'existe pas dans la base' 
            ];
            return new JsonResponse($data,404);
        }
        $form = $this->createForm(PartenaireType::class, $partenaire);
        $form->submit($values);

        if($form->isSubmitted()){
            if (isset($values[$statut])) {
                $partenaire->setStatut($values[$statut]);
            }
            $entityManager->persist($partenaire);
            $entityManager->flush();
            $data = [
                'status6' => 200,
                'message6' => "Le mise à jour du compte a réussi."
            ];
            return new JsonResponse($data,200);
        }
            $data = [
                'status7' => 500,
                'message7' => 'Erreur: vérifiez les champs'
            ];
            return new JsonResponse($data,500);
        
    }

    /**
     * @Route("/lister/compte", name="lister_compte", methods={"POST", "GET"})
     */
    public function listercompte(CompteRepository $compteRepository) : Response
    {
       $compte = $compteRepository->findAll();
       $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
       $normalizers = [new ObjectNormalizer()];
       $serializer = new Serializer($normalizers, $encoders);
       
       // Serialize your object in Json
       $jsonObject = $serializer->serialize($compte, 'json', [
           'compte reference' => function ($object) {
               return $object->getId();
           }
       ]);
       
       // For instance, return a Response with encoded Json
       return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/lister/depot", name="lister_depot", methods={"POST", "GET"})
     */
    public function listerdepot(DepotRepository $depotRepository) : Response
    {
       $depot = $depotRepository->findAll();
       $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
       $normalizers = [new ObjectNormalizer()];
       $serializer = new Serializer($normalizers, $encoders);

       // Serialize your object in Json
       $jsonObject = $serializer->serialize($depot, 'json', [
           'circular_reference_handler' => function ($object) {
               return $object->getId();
           }
       ]);
       
       // For instance, return a Response with encoded Json
       return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
    }

}
