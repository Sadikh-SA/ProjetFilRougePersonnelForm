<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;

class GenererPDFController extends AbstractController
{
    /**
     * @Route("/generer/pdf", name="generer_pdf")
     */
    public function index()
    {
        
        $dompdf = new Dompdf();
        
        // Load HTML to Dompdf
        $dompdf->loadHtml("<h1>CONTRAT DE </h1>");
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream();
    }
}
