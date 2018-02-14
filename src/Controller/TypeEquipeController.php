<?php

namespace App\Controller;

use App\Entity\TypeEquipe;
use App\Form\TypeEquipeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TypeEquipeController extends Controller
{
    /**
     * @Route("/admin/type-equipe/create", name="create-type-equipe")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function create(Request $request){
        $typeEquipe = new TypeEquipe();
        $form = $this->createForm(TypeEquipeType::class, $typeEquipe);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($typeEquipe);
            $em->flush();

            $this->addFlash('success', "La type d'équipe a bien été créé.");
            return $this->redirectToRoute('type-equipe');
        }

        return $this->render('form.html.twig', array(
            'form' => $form->createView(),
            'titre' => "Créer un type d'équipe",
            'back_path' => 'type-equipe'
        ));
    }

    /**
     * @Route("/admin/type-equipe/edit/{id}", name="edit-type-equipe", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $typeEquipe = $em->getRepository(TypeEquipe::class)->find($id);

        if(!$typeEquipe){
            $this->addFlash('danger', "Oops, une erreur est survenue.");
            return $this->redirectToRoute('type-equipe');
        }

        $form = $this->createForm(TypeEquipeType::class, $typeEquipe);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($typeEquipe);
            $em->flush();

            $this->addFlash('success', "Le type d'équipe a bien été modifié.");
            return $this->redirectToRoute('type-equipe');
        }

        return $this->render('form.html.twig', array(
            'form' => $form->createView(),
            'titre' => "Créer un type d'équipe",
            'back_path' => 'type-equipe'
        ));
    }

    /**
     * @Route("/admin/type-equipe", name="type-equipe")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $typeEquipes = $em->getRepository(TypeEquipe::class)->findAll();

        return $this->render('admin/typeEquipe/index.html.twig', array(
            'typeEquipes' => $typeEquipes
        ));
    }
}
