<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;


/**
 * @Route("/filrouge")
 */
class CompteDepotController extends AbstractFOSRestController
{
    /**
     * @Route("/compte/depot/lister", name="compte_depot")
     */
    public function index()
    {
        return $this->render('compte_depot/index.html.twig', [
            'controller_name' => 'CompteDepotController',
        ]);
    }
}
