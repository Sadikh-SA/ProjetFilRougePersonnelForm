<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Profil;
use App\Form\CompteType;
use App\Form\ProfilType;
use App\Entity\Partenaire;
use App\Entity\Utilisateur;
use App\Form\PartenaireType;
use App\Form\UtilisateurType;
use App\Repository\CompteRepository;
use App\Repository\ProfilRepository;
use App\Repository\PartenaireRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @IsGranted({"ROLE_Wari", "ROLE_Admin-Partenaire", "ROLE_Partenaire", "ROLE_Super-Admin"}, message="Vous n'avez pas les droits pour créer un new utilisateur")
     */
    public function ajout(Request $request, UserPasswordEncoderInterface $passwordEncoder, ProfilRepository $profilRepository, PartenaireRepository $partenaireRepository, CompteRepository $compteRepository)
    {
        $user = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $user);
        $values = $request->request->all();
        $file= $request->files->all()["imageName"];
        $form->submit($values);

        $em = $this->getDoctrine()->getManager();
        $errors = [];
        $roleadminpartenaire="ROLE_Admin-Partenaire";
        $rolepartenaire="ROLE_Partenaire";
        $user->setImageFile($file);
        $user->setPassword($passwordEncoder->encodePassword($user, $form->get('password')->getData()));
        $profil = $profilRepository->find($form->get('profil')->getData());
        switch ($profil->getLibelle()) {

            case 'Admin-Partenaire':
                $user->setRoles([$roleadminpartenaire]);
                $idpartenaire = $this->getUser();
                $idcompte = $compteRepository->findByNumeroCompte($values['compte']);
                if (($idpartenaire->getRoles()[0] !=$roleadminpartenaire && $idpartenaire->getRoles()[0]!=$rolepartenaire) || $idcompte==NULL) {
                    $errors[]= "Vous ne pouvez pas créer un Admin-Partenaire: Pas d'accès";
                    if ($idcompte==NULL) {
                        $errors[] = "Ce Compte n'existe pas";
                    }
                }
                $user->setPartenaire($idpartenaire->getPartenaire());
                $user->setCompte($idcompte[0]);
                break;

            case 'Super-Admin':
                $user->setRoles(["ROLE_Super-Admin"]);
                break;

            case 'Caissier':
                $user->setRoles(["ROLE_Caissier"]);
                break;
            
            case 'Utilisateur':
                $user->setRoles(["ROLE_Utilisateur"]);
                $idpartenaire = $this->getUser();
                if ($idpartenaire->getRoles()[0]!=$roleadminpartenaire && $idpartenaire->getRoles()[0]!=$rolepartenaire) {
                    $errors[]= "Vous ne pouvez pas créer un Admin-Partenaire: Pas d'accès";
                }
                $user->setPartenaire($idpartenaire->getPartenaire());
                break;

            case 'Partenaire':
                $iduser = $this->getUser();
                if ($iduser->getRoles()[0]!="ROLE_Super-Admin" && $iduser->getRoles()[0]!="ROLE_Wari") {
                    $errors[]= "Vous ne pouvez pas créer Partenaire car vous n'êtes pas super-admin: Pas d'accès";
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
                $compte->setNumeroCompte(rand(1000000000000,9999999999999));
                $compte->setDateCreation(new \DateTime());
                $compte->setSolde(0);
                $compte->setPartenaire($partenaire);
                $em->persist($compte);
                $user->setCompte($compte);
                break;

            default:
                $errors[] = "Ce Profil n'existe pas";
                break;
        }
        if (!$errors) {
            $user->setStatut(true);
            $user->setDateCreation(new \DateTime());
            $em->persist($user);
            $em->flush();
                return $this->json([
                    'code' => 200,
                    'message4' =>'Utilisateur Inscrit'
                ]);
        }
        return $this->json([
            'errors' => $errors
        ], 400);
        
    }

    /**
     * @Route("/ajoutprofil", name="profil", methods={"POST"})
     * @IsGranted({"ROLE_Wari","ROLE_Super-Admin"}, message="Vous n'avez pas les droits pour créer un new profil")
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
     * @Route("/lister/user", name="lister_user", methods={"POST", "GET"})
     */
    public function listeruser(UtilisateurRepository $utilisateurRepository) : Response
    {
       $user = $utilisateurRepository->findAll();
       $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
       $normalizers = [new ObjectNormalizer()];
       $serializer = new Serializer($normalizers, $encoders);
       
       // Serialize your object in Json
       $jsonObject = $serializer->serialize($user, 'json', [
           'circular_reference_handler' => function ($object) {
               return $object->getId();
           }
       ]);
       
       // For instance, return a Response with encoded Json
       return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
    }


    /**
     * @Route("/lister/partenaire", name="lister_partenaire", methods={"POST", "GET"})
     */
    public function listerpartenaire(PartenaireRepository $partenaireRepository) : Response
    {
       $partenaire = $partenaireRepository->findAll();
       $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
       $normalizers = [new ObjectNormalizer()];
       $serializer = new Serializer($normalizers, $encoders);
       
       // Serialize your object in Json
       $jsonObject = $serializer->serialize($partenaire, 'json', [
           'circular_reference_handler' => function ($object) {
               return $object->getId();
           }
       ]);
       
       // For instance, return a Response with encoded Json
       return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
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
        if (!$user->getStatut()) {
            $data = [
                'status135' => 404,
                'message135' => 'Ton compte est bloqué: Fait appel à ton Propriétaire'
            ];
            return new JsonResponse($data,404);
            
        }
        if ($user->getPartenaire()!=NULL && !$user->getPartenaire()->getStatut()) {
            $data = [
                'status135' => 404,
                'message135' => 'Ton Partenaire est bloqué tu ne peux pas travailler'
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
