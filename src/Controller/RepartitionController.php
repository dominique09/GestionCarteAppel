<?php

namespace App\Controller;

use App\Entity\TypeEquipe;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class RepartitionController extends Controller
{
    /**
     * @Route("/", name="repartition")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $typesEquipe = $em->getRepository(TypeEquipe::class)->findAll();

        return $this->render('repartition/index.html.twig', array(
            'typesEquipe' => $typesEquipe
        ));
    }
}
