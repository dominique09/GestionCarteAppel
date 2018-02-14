<?php

namespace App\Controller;

use App\Entity\Division;
use App\Form\DivisionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DivisionController extends Controller
{
    /**
     * @Route("/admin/division/create", name="create-division")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function create(Request $request){
        $division = new Division();
        $form = $this->createForm(DivisionType::class, $division);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($division);
            $em->flush();

            $this->addFlash('success', "La division a bien été créée.");
            return $this->redirectToRoute('division');
        }

        return $this->render('form.html.twig', array(
            'form' => $form->createView(),
            'titre' => "Créer une division",
            'back_path' => 'division'
        ));
    }

    /**
     * @Route("/admin/division/edit/{id}", name="edit-division", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $division = $em->getRepository(Division::class)->find($id);

        if(!$division){
            $this->addFlash('danger', "Oops, une erreur est survenue.");
            return $this->redirectToRoute('division');
        }

        $form = $this->createForm(DivisionType::class, $division);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($division);
            $em->flush();

            $this->addFlash('success', "La division a bien été modifiée.");
            return $this->redirectToRoute('division');
        }

        return $this->render('form.html.twig', array(
            'form' => $form->createView(),
            'titre' => "Créer une division",
            'back_path' => 'division'
        ));
    }

    /**
     * @Route("/admin/division", name="division")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $divisions = $em->getRepository(Division::class)->findAll();

        return $this->render('admin/division/index.html.twig', array(
            'divisions' => $divisions
        ));
    }
}
