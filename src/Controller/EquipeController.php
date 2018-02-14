<?php

namespace App\Controller;

use App\Entity\Appelant;
use App\Entity\Assignation;
use App\Entity\Carte;
use App\Entity\Division;
use App\Entity\Equipe;
use App\Form\EquipeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EquipeController extends Controller
{
    /**
     * @Route("/equipe/create", name="equipe-create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request){
        $equipe = new Equipe();
        $equipe->setEditingUserInitiales($this->getUser()->getInitiales());
        $form = $this->createForm(EquipeType::class, $equipe);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository(Equipe::class);
            if(count($repo->findActiveByIdentifiant($equipe->getIdentifiant())) > 0)
            {
                $this->addFlash('danger',
                    "Une équipe avec l'identifiant <strong>{$equipe->getIdentifiant()}</strong> existe déjà.");
            } else {
                $equipe->setEditingUserInitiales($this->getUser()->getInitiales());
                $em->persist($equipe);

                $em->flush();

                $this->addFlash("success", "L'équipe {$equipe->getIdentifiant()} a bien été ajouté");

                return $this->redirectToRoute('equipe-create');
            }
        }

        return $this->render('/equipe/create.html.twig', array(
            'titre' => "Création d'une équipe",
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/equipe", name="equipe")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $equipes = $em->getRepository(Equipe::class)->findAll();


        // replace this line with your own code!
        return $this->render('/equipe/index.html.twig',
            [ 'equipes' => $equipes ]);
    }

    /**
     * @Route("/equipe/{id}", name="equipe-details", requirements={"id"="\d+"})
     */
    public function detailsAction($id){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Equipe::class);
        $equipe = $repo->find($id);

        if(!$equipe)
            throw new NotFoundHttpException("L'équipe n'a pas pu être trouvée.");

        return $this->render('/equipe/details.html.twig', [
            'equipe' => $equipe
        ]);
    }

    /**
     * @Route("/api/equipe/repartition", name="api-equipe-repartition")
     */
    public function apiRepartitionAction(){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Equipe::class);
        $equipes = $repo->findActive();

        $updated_at = new \DateTime("1900-01-01");

        $retour = "";
        foreach ($equipes as $equipe){
            if ($equipe->getUpdatedAt() > $updated_at)
                $updated_at = $equipe->getUpdatedAt();

            $retour .= $this->renderView('/equipe/listRepartition.html.twig', array(
                'equipe' => $equipe
            ));
        }

        $updated_at = (!$equipes) ? new \DateTime("2999-01-01"): $updated_at;

        return $this->json([
            'html' => $retour,
            'updated_at' => $updated_at
        ]);
    }

    /**
     * @Route("/api/equipe/equipe-indispo/{id}", name="equipe-indispo", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function apiIndispoAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Equipe::class);
        $equipe = $repo->find($id);

        if(!$equipe)
            throw new NotFoundHttpException("L'équipe n'a pas pu être trouvée.");

        $raison = trim($request->get('raison'));
        if(empty($raison))
            $raison = "N/A";

        $equipe->setEditingUserInitiales($this->getUser()->getInitiales());
        $equipe->setTempsIndispo(new \DateTime());
        $equipe->setRaisonIndispo($raison);

        $em->persist($equipe);
        $em->flush();

        return $this->redirectToRoute('repartition');
    }

    /**
     * @Route("/api/equipe/equipe-debut-dispo/{id}", name="equipe-debut-dispo", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function apiDebutDispoAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Equipe::class);
        $equipe = $repo->find($id);

        if(!$equipe)
            throw new NotFoundHttpException("L'équipe n'a pas pu être trouvée.");

        $equipe->setEditingUserInitiales($this->getUser()->getInitiales());
        $equipe->setDebutDispoAppels(new \DateTime());

        $em->persist($equipe);
        $em->flush();

        return $this->redirectToRoute('repartition');
    }

    /**
     * @Route("/api/equipe/equipe-retour-vers-co/{id}", name="equipe-retour-vers-co", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function apiRetourVersCoAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Equipe::class);
        $equipe = $repo->find($id);

        if(!$equipe)
            throw new NotFoundHttpException("L'équipe n'a pas pu être trouvée.");

        $equipe->setEditingUserInitiales($this->getUser()->getInitiales());
        $equipe->setRetourVersCo(new \DateTime());
        $equipe->setEndroitDestination("CO");

        $em->persist($equipe);
        $em->flush();

        return $this->redirectToRoute('repartition');
    }

    /**
     * @Route("/api/equipe/equipe-fin-dispo/{id}", name="equipe-fin-dispo", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function apiFinDispoAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Equipe::class);
        $equipe = $repo->find($id);

        if(!$equipe)
            throw new NotFoundHttpException("L'équipe n'a pas pu être trouvée.");

        $equipe->setEditingUserInitiales($this->getUser()->getInitiales());
        $equipe->setFinDispoAppels(new \DateTime());
        $equipe->setEndroitPresent($equipe->getEndroitDestination());
        $equipe->setEndroitDestination(null);

        $em->persist($equipe);
        $em->flush();

        return $this->redirectToRoute('repartition');
    }

    /**
     * @Route("/api/equipe/equipe-dissoudre/{id}", name="equipe-dissoudre", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function apiDissoudreAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Equipe::class);
        $equipe = $repo->find($id);

        if(!$equipe)
            throw new NotFoundHttpException("L'équipe n'a pas pu être trouvée.");

        $equipe->setEditingUserInitiales($this->getUser()->getInitiales());
        $equipe->setDissolvedAt(new \DateTime());

        $em->persist($equipe);
        $em->flush();

        return $this->redirectToRoute('repartition');
    }

    /**
     * @Route("/api/equipe/dispo/{id}", name="equipe-dispo", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function apiDispoAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Equipe::class);
        $equipe = $repo->find($id);

        if(!$equipe)
            throw new NotFoundHttpException("L'équipe n'a pas pu être trouvée.");

        $equipe->setEditingUserInitiales($this->getUser()->getInitiales());
        $equipe->setFinDispoAppels(null);
        $equipe->setRaisonIndispo(null);
        $equipe->setTempsIndispo(null);
        $equipe->setRetourVersCo(null);

        $em->persist($equipe);
        $em->flush();

        return $this->redirectToRoute('repartition');
    }

    /**
     * @Route("/api/equipe/direction/{id}", name="equipe-direction", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function apiDestinationAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Equipe::class);
        $equipe = $repo->find($id);

        if(!$equipe)
            throw new NotFoundHttpException("L'équipe n'a pas pu être trouvée.");

        $direction = trim($request->get('direction'));
        if(empty($direction))
            $direction = "N/A";

        $equipe->setEditingUserInitiales($this->getUser()->getInitiales());
        $equipe->setEndroitDestination($direction);

        $em->persist($equipe);
        $em->flush();

        return $this->redirectToRoute('repartition');
    }

    /**
     * @Route("/api/equipe/sur-place/{id}", name="equipe-sur-place", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function apiSurPlaceAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Equipe::class);
        $equipe = $repo->find($id);

        if(!$equipe)
            throw new NotFoundHttpException("L'équipe n'a pas pu être trouvée.");

        $equipe->setEditingUserInitiales($this->getUser()->getInitiales());
        $equipe->setEndroitPresent($equipe->getEndroitDestination());
        $equipe->setEndroitDestination(null);

        $em->persist($equipe);
        $em->flush();

        return $this->redirectToRoute('repartition');
    }

    /**
     * @Route("/api/equipe/intercepte/{id}", name="equipe-intercepte", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function apiIntercepteAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Equipe::class);
        $repo_appelant = $em->getRepository(Appelant::class);
        $equipe = $repo->find($id);
        $appelantDef = $repo_appelant->findBy("name = 'ASJ'");

        if(!$equipe)
            throw new NotFoundHttpException("L'équipe n'a pas pu être trouvée.");

        $endroit = $request->get('endroit');
        if(!$endroit)
            $endroit = "N/A";
        $clawson = $request->get('clawson');
        if(!$clawson)
            $clawson = "N/A";
        $details = $request->get('details');
        if(!$details)
            $details = "N/A";

        $equipe->setEditingUserInitiales($this->getUser()->getInitiales());

        $carte = new Carte();
        $carte->setEmplacement($endroit);
        $carte->setClawson($clawson);
        $carte->setDescription($details);

        $assignation = new Assignation();
        $assignation->setCarte($carte);
        $assignation->setEquipe($equipe);
        $assignation->setDispatchedAt(new \DateTime());
        $assignation->setDirectionPatientAt(new \DateTime());

        $em->persist($equipe);
        $em->persist($assignation);
        $em->persist($carte);

        $em->flush();

        return $this->redirectToRoute('repartition');
    }



}
