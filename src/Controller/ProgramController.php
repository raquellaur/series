<?php
//src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\CommentType;
use App\Form\ProgramType;
use App\Service\Slugify;
use App\Entity\User;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Email;

/**
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
     * @Route("/new", name="new")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param Slugify $slugify
     * @return Response
     * @param MailerInterface $mailer
     */
    public function new(EntityManagerInterface $entityManager, Request $request, Slugify $slugify, MailerInterface $mailer) : Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $entityManager->persist($program);
            $entityManager->flush();
            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('your_email@example.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('program/newProgramEmail.html.twig', ['program' => $program]));

            $mailer->send($email);
            return $this->redirectToRoute('program_index');
        }
        return $this->render('program/new.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * Getting a program by id
     * @return Response
     * @Route("/show/{slug}", name="show")
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
     * @Route("/{slug}/edit", name="edit", methods={"GET","POST"})
     * @param Slugify $slugify
     */
    public function edit(Request $request, Program $program, Slugify $slugify): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/{programSlug}/seasons/{seasonId}",  name="season_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping" : {"programSlug" : "slug"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping" : {"seasonId" : "id"}})
     * @param Program $program
     * @param Season $season
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
     * @Route("/{programSlug}/seasons/{seasonId}/episodes/{episodeSlug}",  name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping" : {"programSlug" : "slug"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping" : {"seasonId" : "id"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping" : {"episodeSlug" : "slug"}})
     * @param Season $season
     * @param Episode $episode
     * @param Program $program
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @var User $user
     */
    public function showEpisode(
        Request $request,
        EntityManagerInterface $entityManager,
        Program $program,
        Season $season,
        Episode $episode)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $comment->setAuthor($user);
            $comment->setEpisode($episode);
            $entityManager->persist($comment);
            $entityManager->flush();


        }
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
            'form' => $form->createView(),
        ]);

    }
}