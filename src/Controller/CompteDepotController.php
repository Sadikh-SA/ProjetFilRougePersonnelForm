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
use App\Repository\UtilisateurRepository;
use App\Repository\ProfilRepository;
use App\Repository\PartenaireRepository;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
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
                'messages' =>'Nouveau Compte Ajouté'
            ]);
        }
        return $this->handleView($this->view($validator->validate($form)));
    }



    /**
     * @Route("/partenaire/ajouter", name="compte_ajouter", methods={"POST","GET"})
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
                    'message6' =>'Nouveau Partenaire Ajouté'
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
            $profil = $profilRepository->findByLibelle($values['profil']);
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
     * @Route("/bloquer/users", name="bloquer_user_1", methods={"POST", "PUT"})
     */
    public function bloquerUsers(Request $request, UtilisateurRepository $UtilisateurRepository , EntityManagerInterface $entityManager)
    {
        $values =json_decode($request->getContent(),true);
        $user = $UtilisateurRepository->findByUsername($values['username']);
        if (!$user) {
           return $this->json([
                'code' => 404,
                'messagee' => 'Ce user n\'existe pas dans la base'
            ]);
        }
        if ($user[0]->getProfil() != "9" && $this->getUser()->getProfil() == "9") {
            if ($user[0]->getStatut()) {
                $user[0]->setStatut(false);
                $entityManager->flush();
                return $this->json([
                    'code' => 200,
                    'message' => 'vous avez bloquer ce user'
                ]);
            } else {
                $user[0]->setStatut(true);
                $entityManager->flush();
                return $this->json([
                    'code' => 200,
                    'message' => 'vous avez activer ce user'
                ]);
            }
        }else if($user[0]->getProfil() != "11" && $this->getUser()->getProfil() == "11"){
            if ($user[0]->getStatut()) {
                $user[0]->setStatut(false);
                $entityManager->flush();
                return $this->json([
                    'code' => 200,
                    'message' => 'vous avez bloquer ce user'
                ]);
            } else {
                $user[0]->setStatut(true);
                $entityManager->flush();
                return $this->json([
                    'code' => 200,
                    'message' => 'vous avez activer ce user'
                ]);
            }
        }else {
            return $this->json([
                    'code' => 403,
                    'message' => 'Ce user ne peut pas être bloqué'
                ]);
        }


        
        
    }

    /**
     * @Route("/bloquer/partenaire", name="bloquer_unpartenaire", methods={"PUT","POST"})
     */
    public function BloquerParte(Request $request, PartenaireRepository $partenaireRepository , EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $values = $request->request->all();
        $partenaire = $partenaireRepository->findByNinea($values['ninea']);
        if (!$partenaire) {
           return $this->json([
                'code' => 404,
                'messagee' => 'Ce partenaire n\'existe pas dans la base'
            ]);
        }

        if ($partenaire[0]->getStatut()) {
            $partenaire[0]->setStatut(false);
            $entityManager->flush();
            return $this->json([
                'code' => 200,
                'message' => 'vous avez bloquer ce partenaire'
            ]);
        } else {
            $partenaire[0]->setStatut(true);
            $entityManager->flush();
            return $this->json([
                'code' => 200,
                'message' => 'vous avez activer ce partenaire'
            ]);
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
     * @IsGranted({"ROLE_Wari", "ROLE_Admin-Partenaire", "ROLE_Partenaire", "ROLE_Super-Admin"}, message="Vous n'avez pas les droits pour modifier un partenaire")
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
     * @IsGranted({"ROLE_Wari", "ROLE_Super-Admin"}, message="Vous n'avez pas les droits pour lister les comptes")
     */
    public function listercompte(CompteRepository $compteRepository, SerializerInterface $serializer)
    {
       $compte = $compteRepository->findAll();
       $data = $serializer->serialize($compte, 'json', ['groups' => ['compte']]);
    
       
        return new Response(
           $data,200,[
               'Content-Type' => 'application/json'
           ]
        );
    }

    /**
     * @Route("/lister/depot", name="lister_depot", methods={"POST", "GET"})
     * @IsGranted({"ROLE_Caissier"}, message="Vous n'avez pas les droits pour lister les dépots")
     */
    public function listerdepot(DepotRepository $depotRepository, SerializerInterface $serializer)
    {
       $depot = $depotRepository->findAll();
       $data = $serializer->serialize($depot, 'json', ['groups' => ['depot']]);
    
       
        return new Response(
           $data,200,[
               'Content-Type' => 'application/json'
           ]
        );
    }


    /**
     * @Route("/select/user", name="lister_un_utilisateur_quelconque", methods={"POST","GET"})
     * @IsGranted({"ROLE_Wari", "ROLE_Admin-Partenaire", "ROLE_Partenaire", "ROLE_Super-Admin"}, message="Vous n'avez pas les droits")
     */
    public function selectUser(Request $request, UtilisateurRepository $UtilisateurRepository, SerializerInterface $serializer)
    {
        $values = $request->request->all();
        $user = $UtilisateurRepository->findByUsername($values['username']);
        $data = $serializer->serialize($user, 'json', ['groups' => ['utilisateur']]);
    
       
        return new Response(
           $data,200,[
               'Content-Type' => 'application/json'
           ]
        );

    }
    /**
     * @Route("/select/compte", name="lister_un_compte_quelconque", methods={"POST","GET"})
     * @IsGranted({"ROLE_Wari", "ROLE_Admin-Partenaire", "ROLE_Partenaire", "ROLE_Super-Admin", "ROLE_Caissier"}, message="Vous n'avez pas les droits ")
     */
    public function selectCompte(Request $request, CompteRepository $CompteRepository, SerializerInterface $serializer)
    {
        $values = $request->request->all();
        $compte = $CompteRepository->findByNumeroCompte($values['numeroCompte']);
        $data = $serializer->serialize($compte, 'json', ['groups' => ['compte']]);
    
       
        return new Response(
           $data,200,[
               'Content-Type' => 'application/json'
           ]
        );

    }

    /**
     * @Route("/select/partenaire", name="lister_un_partenaire_quelconque", methods={"POST","GET"})
     * @IsGranted({"ROLE_Wari", "ROLE_Admin-Partenaire", "ROLE_Partenaire", "ROLE_Super-Admin"}, message="Vous n'avez pas les droits")
     */
    public function selectPartenaire(Request $request, PartenaireRepository $PartenaireRepository, SerializerInterface $serializer)
    {
        $values = $request->request->all();
        $partenaire = $PartenaireRepository->findByNinea($values['ninea']);
        $data = $serializer->serialize($partenaire, 'json', ['groups' => ['partenaire']]);
    
       
        return new Response(
           $data,200,[
               'Content-Type' => 'application/json'
           ]
        );

    }

    /**
     * @Route("/select/profil", name="lister_un_profil_quelconque", methods={"POST","GET"})
     * @IsGranted({"ROLE_Wari", "ROLE_Super-Admin"}, message="Vous n'avez pas les droits ")
     */
    public function selectProfil(Request $request, ProfilRepository $ProfilRepository, SerializerInterface $serializer)
    {
        $values = $request->request->all();
        $profil = $ProfilRepository->findByLibelle($values['libelle']);
        $data = $serializer->serialize($profil, 'json');
    
       
        return new Response(
           $data,200,[
               'Content-Type' => 'application/json'
           ]
        );

    }

    /**
     * @Route("/lister/profil", name="lister_tous_profil", methods={"POST","GET"})
     * @IsGranted({"ROLE_Wari", "ROLE_Super-Admin"}, message="Vous n'avez pas les droits")
     */
    public function listerProfil(ProfilRepository $ProfilRepository, SerializerInterface $serializer)
    {
        $profil = $ProfilRepository->findAll();
        $data = $serializer->serialize($profil, 'json');
    
       
        return new Response(
           $data,200,[
               'Content-Type' => 'application/json'
           ]
        );

    }


    /**
     * @Route("/attribuer/compte", name="attribuer_compte", methods={"POST", "PUT"})
     * @IsGranted({"ROLE_Partenaire", "ROLE_Admin-Partenaire"}, message="Vous n'avez pas les droits ")
     */
    public function attribuerCompte(Request $request, UtilisateurRepository $UtilisateurRepository , CompteRepository $compteRepository , EntityManagerInterface $entityManager)
    {
        $values = $request->request->all();
        $values =json_decode($request->getContent(),true);
        $user = $UtilisateurRepository->findByUsername($values['username']);
        $compte = $compteRepository->findByNumeroCompte($values['compte']);
        if (!$user) {
           return $this->json([
                'code' => 404,
                'messagee' => 'Ce user n\'existe pas dans la base'
            ]);
        }
        if (!$compte) {
            return $this->json([
                'code' => 404,
                'messagee' => 'Ce compte n\'existe pas dans la base'
            ]);
        }

            $user[0]->setCompte($compte[0]);
            $entityManager->flush();
            return $this->json([
                'code' => 200,
                'message' => 'vous avez attribuer un compte à '.$values['username']
            ]);
    }

    /**
     * @Route("/lister/compte/un/partenaire", name="listuer_compte", methods={"POST", "GET"})
     * @IsGranted({"ROLE_Partenaire", "ROLE_Admin-Partenaire"}, message="Vous n'avez pas les droits ")
     */
    public function FunctionName(SerializerInterface $serializer)
    {
        $compte = $this->getUser()->getPartenaire()->getComptes();
        if (!$compte) {
             return $this->json([
                'code' => 404,
                'message' => 'Ce Compte n\'existe pas dans la base'
            ]);
        }
        $data = $serializer->serialize($compte, 'json');
    
       
        return new Response(
           $data,200,[
               'Content-Type' => 'application/json'
           ]
        ); 
    }
}
