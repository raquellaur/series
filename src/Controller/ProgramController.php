<?php
//src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @return Response
 * @Route("/programs", name="program_")
 */
Class   ProgramController extends AbstractController
{
    /**
     * @return Response
     * @Route("/", name="index")
     */
    public function index(): Response
    {
       return $this->render('program/index.html.twig', [
           'website' => 'Wild Series'
       ]);
    }
}