<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use App\Entity\Partenaire;
use App\Form\PartenaireType;
use App\Form\CompteType;
use App\Entity\Compte;
use App\Form\ProfilType;
use App\Entity\Profil;
use App\Repository\ProfilRepository;
use App\Repository\PartenaireRepository;
use App\Repository\CompteRepository;

/**
 * @Route("/filrouge")
 */
class FilRougeController extends AbstractFOSRestController
{


    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder )
    {
        $this->passwordEncoder = $passwordEncoder;
    }


    /**
     * @Route("/lister/user", name="lister_user")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Utilisateur::class);
        $user = $repo->findAll();
        return $this->handleView($this->view($user));
    }


    /**
     * @Route("/ajouter/partenaire", name="ajouter_les_3", methods={"POST"})
     */
    public function ajout(Request $request, UserPasswordEncoderInterface $passwordEncoder, ProfilRepository $profilRepository, PartenaireRepository $partenaireRepository, CompteRepository $compteRepository)
    {
        $values = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $user = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $user);
        $form->submit($values);
        $errors = [];
        $error = [];
        $roleadminpartenaire="ROLE_Admin-Partenaire";
        $rolepartenaire="ROLE_Partenaire";
        $user->setPassword($passwordEncoder->encodePassword($user, $form->get('password')->getData()));
        $profil = $profilRepository->find($form->get('profil')->getData());
        if (!$profil) {
            $errors[] = "Ce Profil n'existe pas";
        }elseif ($profil->getLibelle()=="Admin-Partenaire") {
            $user->setRoles([$roleadminpartenaire]);
            $idpartenaire = $this->getUser();
            $idcompte = $compteRepository->find($values['compte']);
            if (($idpartenaire->getRoles()[0]!=$roleadminpartenaire || $idpartenaire->getRoles()[0]!=$rolepartenaire) || $idcompte==NULL) {
                $error[]= "Vous ne pouvez pas créer un Admin-Partenaire: Pas d'accès";
                $errors[] = "Ce Compte n'existe pas";
            }
            $user->setPartenaire($idpartenaire->getPartenaire());
            $user->setCompte($idcompte);
        }
        elseif ($profil->getLibelle()=="Super-Admin") {
            $user->setRoles(["ROLE_Super-Admin"]);
        }elseif ($profil->getLibelle()=="Caissier") {
            $user->setRoles(["ROLE_Caissier"]);
        }elseif ($profil->getLibelle()=="Utilisateur") {
            $user->setRoles(["ROLE_Utilisateur"]);
            $idpartenaire = $this->getUser();
            $idcompte = $compteRepository->find($values['compte']);
            if ($idcompte==NULL || ($idpartenaire->getRoles()[0]!=$roleadminpartenaire || $idpartenaire->getRoles()[0]!=$rolepartenaire)) {
                $errors[] = "Ce Compte n'existe pas";
                $error[]= "Vous ne pouvez pas créer un Admin-Partenaire: Pas d'accès";
            }
            $user->setPartenaire($idpartenaire->getPartenaire());
            $user->setCompte($idcompte);
        }
        elseif ($profil->getLibelle()=="Partenaire") {
            $iduser = $this->getUser();
            if ($iduser->getRoles()[0]!="ROLE_Super-Admin" || $iduser->getRoles()[0]!="ROLE_Wari") {
                $error[]= "Vous ne pouvez pas créer Partenaire car vous n'êtes pas super-admin: Pas d'accès";
            }
            $user->setRoles(["ROLE_Partenaire"]);
            #AJOUTER NOUVEAU PARTENAIRE
            $partenaire = new Partenaire();
            $form1 = $this->createForm(PartenaireType::class, $partenaire);
            $form1->submit($values);
            $partenaire->setDateCreation(new \DateTime());
            $partenaire->setStatut(true);
            $em->persist($partenaire);
            $user->setPartenaire($partenaire);
            #AJOUTER NOUVEAU COMPTE POUR CE PARTENAIRE
            $compte = new Compte();
            $form2 = $this->createForm(CompteType::class, $compte);
            $form2->submit($values);
            $compte->setDateCreation(new \DateTime());
            $compte->setPartenaire($partenaire);
            $em->persist($compte);
            $user->setCompte($compte);
        }
        if (!$errors && !$error) {
            $user->setStatut(true);
            $user->setDateCreation(new \DateTime());
            $em->persist($user);
            $em->flush();
                return $this->json([
                    'code' => 200,
                    'message' =>'Utilisateur Inscrit'
                ]);
        }
        elseif ($errors) {
            return $this->json([
                'errors' => $errors
            ], 400);
        }
        return $this->json([
            'error' => $error
        ], 400);
        
    }

    /**
     * @Route("/ajoutprofil", name="profil", methods={"POST"})
     */
    public function NewProfil(Request $request)
    {
        $values = $request->request->all();
        $connect = $this->getDoctrine()->getManager();
        $profil = new Profil();
        $form = $this->createForm(ProfilType::class, $profil);
        $form->submit($values);
        if($form->isSubmitted() && $form->isValid()){
            $connect->persist($profil);
            $connect->flush();
                return $this->json([
                    'code' => 200,
                    'message' =>'Nouveau Profil Ajouté'
                ]);
        }
        return $this->json([
            'status' => 500,
            'message0' =>'Une erreurs s\'est produite: il y\'a des champs manquantes ou le profil existe déja'
        ]);

    }



    /**
     * @Route("/login", name="login", methods={"POST"})
     * @param JWTEncoderInterface $JWTEncoder
     * @param JsonResponse
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException
     */
    public function login(Request $request , JWTEncoderInterface $JWTEncoder)
    {

        $values = json_decode($request->getContent());
        $user = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy([
            'username' => $values->username,
        ]);

        $isValid = $this->passwordEncoder->isPasswordValid($user, $values->password);
        if (!$isValid || !$user) {
            $data = [
                'code' => 401,
                'messag' => 'Username ou Mot de Passe incorrecte'
            ];
            return new JsonResponse($data,300);
        }
        if (!$user->getStatut()) {
            $data = [
                'status135' => 404,
                'message135' => 'Ce compte est Bloqué'
            ];
            return new JsonResponse($data,404);
            
        }
        $token = $JWTEncoder->encode([
                'email' => $user->getEmail(),
                'exp' => time() + 3600 // 1 hour expiration
            ]);
        return $this->json([
            'token' => $token
        ]);
    }       
}
