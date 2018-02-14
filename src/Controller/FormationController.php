<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormationController extends Controller
{
    /**
     * @Route("/admin/formation/create", name="create-formation")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function create(Request $request){
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($formation);
            $em->flush();

            $this->addFlash('success', "La formation a bien été créée.");
            return $this->redirectToRoute('formation');
        }

        return $this->render('form.html.twig', array(
            'form' => $form->createView(),
            'titre' => "Créer une formation",
            'back_path' => 'formation'
        ));
    }

    /**
     * @Route("/admin/formation/edit/{id}", name="edit-formation", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $formation = $em->getRepository(Formation::class)->find($id);

        if(!$formation){
            $this->addFlash('danger', "Oops, une erreur est survenue.");
            return $this->redirectToRoute('formation');
        }

        $form = $this->createForm(FormationType::class, $formation);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($formation);
            $em->flush();

            $this->addFlash('success', "La formation a bien été modifiée.");
            return $this->redirectToRoute('formation');
        }

        return $this->render('form.html.twig', array(
            'form' => $form->createView(),
            'titre' => "Créer une formation",
            'back_path' => 'formation'
        ));
    }

    /**
     * @Route("/admin/formation", name="formation")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $formations = $em->getRepository(Formation::class)->findAll();


        return $this->render('admin/formation/index.html.twig', array(
            'formations' => $formations
        ));
    }
}
