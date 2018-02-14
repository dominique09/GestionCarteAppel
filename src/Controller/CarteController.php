<?php

namespace App\Controller;

use App\Entity\Assignation;
use App\Entity\Carte;
use App\Entity\Equipe;
use App\Entity\TypeEquipe;
use App\Form\CarteCreateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CarteController extends Controller
{
    /**
     * @Route("/carte/create", name="carte-create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request){
        $carte = new Carte();
        $carte->setEditingUserInitiales($this->getUser()->getInitiales());
        $form = $this->createForm(CarteCreateType::class, $carte);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();

            $carte->setEditingUserInitiales($this->getUser()->getInitiales());
            $em->persist($carte);

            $em->flush();

            $this->addFlash("success", "La carte {$carte->getId()} a bien été créée.");

            return $this->redirectToRoute('repartition');

        }

        return $this->render('/carte/create.html.twig', array(
            'titre' => "Création d'une équipe",
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/api/carte/repartition", name="api-carte-repartition")
     */
    public function apiRepartitionAction(){
        $em = $this->getDoctrine()->getManager();
        $carte_repo = $em->getRepository(Carte::class);
        $equipe_repo = $em->getRepository(Equipe::class);
        $type_equipe_repo = $em->getRepository(TypeEquipe::class);
        $cartes = $carte_repo->findActive();
        $equipes = $equipe_repo->findAllDispo();
        $types_equipe = $type_equipe_repo->findAll();

        $updated_at = new \DateTime("1900-01-01");

        $retour = "";
        foreach ($cartes as $carte){

            if ($carte->getUpdatedAt() > $updated_at)
                $updated_at = $carte->getUpdatedAt();

            $retour .= $this->renderView('/carte/listRepartition.html.twig', array(
                'carte' => $carte,
                'equipes' => $equipes,
                'types_equipe' => $types_equipe
            ));
        }

        $updated_at = (!$cartes) ? new \DateTime("2999-01-01"): $updated_at;

        return $this->json([
            'html' => $retour,
            'updated_at' => $updated_at
        ]);
    }

    /**
     * @Route("/api/carte/assigner/{carte_id}/{equipe_id}", name="api-carte-assigner", requirements={"carte_id"="\d+", "equipe_id"="\d+"})
     */
    public function apiAssignerAction($carte_id, $equipe_id){
        $em = $this->getDoctrine()->getManager();
        $carte_repo = $em->getRepository(Carte::class);
        $equipe_repo = $em->getRepository(Equipe::class);
        $assignation_repo = $em->getRepository(Assignation::class);
        $carte = $carte_repo->find($carte_id);
        $equipe = $equipe_repo->find($equipe_id);

        if(!$carte || ! $equipe)
            throw new NotFoundHttpException("Oops.");

        if(count($assignation_repo->findByCarteEtEquipeEnCours($carte, $equipe)) > 0){
            $this->addFlash('danger', "Cette équipe a déjà une assignation en cours sur cette carte.");
        }
        else {

            $assignation = new Assignation();
            $carte->addAssignation($assignation);
            $equipe->addAssignation($assignation);

            $carte->setUpdatedAt(new \DateTime());
            $equipe->setUpdatedAt(new \DateTime());

            if($equipe->getTypeEquipe()->getName() == "Volante") {
                $carte->setVolanteDemande(false);
            }

            $em->persist($carte);
            $em->persist($equipe);
            $em->flush();
        }

        return $this->redirectToRoute('repartition');
    }

    /**
     * @Route("/api/carte/annuler/{id}", name="api-annuler-carte", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function apiAnnulerAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $carte_repo = $em->getRepository(Carte::class);
        $carte = $carte_repo->find($id);

        if(!$carte)
            throw new NotFoundHttpException("Oops.");

        $carte->setEditingUserInitiales($this->getUser()->getInitiales());
        $raison = $request->get('raison');
        if(!$raison)
            $raison = "N/A";

        foreach ($carte->getAssignationsEnCours() as $assignation){
            $assignation->setCancelledAt(new \DateTime());
            $em->persist($assignation);
        }

        $carte->setRaisonCancelled($raison);
        $carte->setCancelledAt(new \DateTime());

        $em->persist($carte);
        $em->flush();

        return $this->redirectToRoute('repartition');
    }

    /**
     * @Route("/api/carte/demanderVolante/{id}", name="api-carte-demander-volante", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function apiDemanderVolanteAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $carte_repo = $em->getRepository(Carte::class);
        $carte = $carte_repo->find($id);

        if(!$carte)
            throw new NotFoundHttpException("Oops.");

        $carte->setVolanteDemande(true);

        $em->persist($carte);
        $em->flush();

        return $this->redirectToRoute('repartition');
    }
    /**
     * @Route("/api/carte/annuler-volante/{id}", name="api-carte-annuler-volante", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function apiAnnulerVolanteAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $carte_repo = $em->getRepository(Carte::class);
        $carte = $carte_repo->find($id);

        if(!$carte)
            throw new NotFoundHttpException("Oops.");

        $carte->setVolanteDemande(false);

        $em->persist($carte);
        $em->flush();

        return $this->redirectToRoute('repartition');
    }
}
