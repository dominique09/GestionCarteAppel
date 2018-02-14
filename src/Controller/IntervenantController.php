<?php

namespace App\Controller;

use App\Entity\Intervenant;
use App\Form\IntervenantCreateType;
use App\Form\IntervenantEditType;
use App\Repository\IntervenantRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IntervenantController extends Controller
{
    private $_interRopo;

    public function __construct(IntervenantRepository $intervenantRepository)
    {
        $this->_interRopo = $intervenantRepository;
    }

    /**
     * @Route("/intervenant", name="intervenant")
     */
    public function index()
    {
        $intervenants = $this->getDoctrine()
            ->getRepository(Intervenant::class)
            ->findAll();

        return $this->render('intervenant/index.html.twig',
            ['intervenants' => $intervenants]);
    }

    /**
     * @Route("/intervenant/create", name="create-intervenant")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function create(Request $request){
        $intervenant = new Intervenant();

        $form = $this->createForm(IntervenantCreateType::class, $intervenant);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $intervenant->setInitiales($this->ObtenirInitiales($intervenant));

            $em = $this->getDoctrine()->getManager();
            $em->persist($intervenant);
            $em->flush();

            $this->addFlash(
                'success',
                "L'intervenant {$intervenant->getFirstname()} {$intervenant->getLastname()} ({$intervenant->getInitiales()}) a été ajouté."
            );

            return $this->redirectToRoute('intervenant');
        }

        return $this->render('form.html.twig',
            ['form' => $form->createView(),
                'titre' => "Création d'un intervenant",
                'back_path' => 'intervenant']);
    }

    /**
     * @Route("/intervenant/edit/{id}", name="edit-intervenant", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function edit($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $intervenant = $em->getRepository(Intervenant::class)->find($id);

        if(!$intervenant){
            $this->addFlash('danger', "Oops, une erreur est survenue.");
            return $this->redirectToRoute('intervenant');
        }

        $form = $this->createForm(IntervenantEditType::class, $intervenant);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($intervenant);
            $em->flush();

            $this->addFlash('success', "L'intervenant a bien été modifié.");
        }

        return $this->render('form.html.twig',
            ['form' => $form->createView(),
                'titre' => "Modification d'un intervenant",
                'back_path' => 'intervenant']);
    }

    /**
     * @param Intervenant $intervenant
     * @return string
     */
    private function ObtenirInitiales(Intervenant $intervenant){
        $initialesVouluesBase = strtoupper(
            substr($intervenant->getFirstname(), 0, 1) .
            substr($intervenant->getLastname(), 0, 1)
        );

        $initialesVoulues = $initialesVouluesBase;

        $i = 1;
        while(count($this->_interRopo->findByInitiales($initialesVoulues)) > 0){
            $initialesVoulues = $initialesVoulues . $i;
            $i++;
        }

        return $initialesVoulues;
    }
}
