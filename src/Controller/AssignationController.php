<?php

namespace App\Controller;

use App\Entity\Assignation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AssignationController extends Controller
{
    /**
     * @Route("/api/assignation/etape/{id}/{etape}", name="api-assignation-etape", requirements={"id"="\d+"})
     * @param $id
     * @param $etape
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function etapeAction($id, $etape)
    {
        $em = $this->getDoctrine()->getManager();
        $ass_repo = $em->getRepository(Assignation::class);
        $assignation = $ass_repo->find($id);

        if(!$etape)
            throw new \InvalidArgumentException("Aucune étape mentionnée");

        if(!$assignation)
            throw new NotFoundHttpException("Oops.");

        $assignation->setProperty($etape, new \DateTime());

        $assignation->getCarte()->setUpdatedAt(new \DateTime());
        $assignation->getEquipe()->setUpdatedAt(new \DateTime());

        $em->persist($assignation);
        $em->persist($assignation->getCarte());
        $em->persist($assignation->getEquipe());
        $em->flush();

        return $this->redirectToRoute('repartition');
    }

    /**
     * @Route("/api/assignation/backward/{id}", name="api-assignation-backward", requirements={"id"="\d+"})
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function backwardAction($id){
        $em = $this->getDoctrine()->getManager();
        $ass_repo = $em->getRepository(Assignation::class);
        $assignation = $ass_repo->find($id);

        if(!$assignation)
            throw new NotFoundHttpException("Oops.");

        $this->backward($assignation);

        $assignation->getCarte()->setUpdatedAt(new \DateTime());
        $assignation->getEquipe()->setUpdatedAt(new \DateTime());

        $em->persist($assignation);
        $em->persist($assignation->getCarte());
        $em->persist($assignation->getEquipe());
        $em->flush();

        return $this->redirectToRoute('repartition');
    }

    private function backward(Assignation $assignation){
        if($assignation->getCancelledAt()) {
            $assignation->setCancelledAt(null);
            return;
        }

        if($assignation->getClosedAt()) {
            $assignation->setClosedAt(null);
            return;
        }

        if($assignation->getArrivedChAt()) {
            $assignation->setArrivedChAt(null);
            return;
        }

        if($assignation->getDirectionChAt()){
            $assignation->setDirectionChAt(null);
            return;
        }

        if($assignation->getArrivedPatientAt()){
            $assignation->setArrivedPatientAt(null);
            return;
        }

        if($assignation->getDirectionPatientAt()){
            $assignation->setDirectionPatientAt(null);
            return;
        }

        if($assignation->getDispatchedAt()){
            $assignation->setDispatchedAt(null);
            return;
        }
    }
}
