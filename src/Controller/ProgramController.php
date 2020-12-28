<?php
//src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramType;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @return Response
 * @Route("/programs", name="program_")
 */
class ProgramController extends AbstractController
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
     * @param Request $request
     * @Route("/new", name="new")
     */
    public function new(Request $request)
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($program);
            $entityManager->flush();
            return $this->redirectToRoute('program_index');
        }
        return $this->render('program/new.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * Getting a program by id
     * @return Response
     * @Route("/show/{id<^[0-9]+$>}", name="show")
     */
    public function show(Program $program): Response
    {
        $seasons = $program->getSeasons();

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Program $program): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param int $programId
     * @param int $seasonId
     * @Route ("/{program}/seasons/{season}", name="season_show")
     * @return Response
     */
    public function showSeason(Program $program, Season $season): Response
    {
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }

    /**
     * @Route("/{programId}/seasons/{seasonId}/episodes/{episodeId}",  name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping" : {"programId" : "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping" : {"seasonId" : "id"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping" : {"episodeId" : "id"}})
     * @param Season $season
     * @param Episode $episode
     * @param Program $program
     */
    public function showEpisode(Program $program, Season $season, Episode $episode)
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode
        ]);

    }
}