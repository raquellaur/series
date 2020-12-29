<?php

namespace App\Controller;

use App\Entity\Actor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/actor", name="actor_")
 */
class ActorController extends AbstractController
{
    /**
     * @param Actor $actor
     * @Route ("/show/{id<^[0-9]+$>}", name="show")
     * @return Response
     */
    public function show(Actor $actor) : Response
    {
        return $this->render("actor/show.html.twig", [
            'actor' => $actor
        ]);

    }
}