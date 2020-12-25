<?php
//src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Program;
use phpDocumentor\Reflection\Types\Integer;
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
        $proprams = $this->getDoctrine()
            ->getRepository(Program::Class)
            ->findAll();
       return $this->render('program/index.html.twig', [
           'programs' => $proprams
       ]);
    }

    /**
     * Getting a program by id
     * @return Response
     * @Route("/show/{id<^[0-9]+$>}", name="show")
     * @param $id
     */
    public function show(int $id): Response
    {
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' => $id]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program found for id ' . $id
            );
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);

    }
}