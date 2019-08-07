<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Utilisateur; 
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UtilisateurFixtures extends Fixture
{
    private $passwordEncoder;
    
    public function __construct( UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        
        $user = new Utilisateur();
                $user->setEmail("abougueye96@yahoo.fr");
                $user->setPassword($this->passwordEncoder->encodePassword($user, "Moimeme"));
                $user->setPrenom("Ababacar Sadikh");
                $user->setNom("GUEYE");
                $user->setAdresse("101 Hamo 4 Guédiawaye");
                $user->setTel("784408822");
                $user->setProfil("Super-Admin");
                $user->setRoles(['ROLE_Wari']);
                $user->setUsername("Sadikh");
                $user->setStatut(true);
                $user->setDateCreation(new \DateTime());
        $manager->persist($user);

        $manager->flush();
    }
}
