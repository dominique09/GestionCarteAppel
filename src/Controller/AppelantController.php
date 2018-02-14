<?php

namespace App\Controller;

use App\Entity\Appelant;
use App\Form\AppelantType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AppelantController extends Controller
{
    /**
     * @Route("/admin/appelant/create", name="create-appelant")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function create(Request $request){
        $appelant = new Appelant();
        $form = $this->createForm(AppelantType::class, $appelant);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($appelant);
            $em->flush();

            $this->addFlash('success', "L'appelant a bien été créé.");
            return $this->redirectToRoute('appelant');
        }

        return $this->render('form.html.twig', array(
            'form' => $form->createView(),
            'titre' => "Créer un appelant",
            'back_path' => 'appelant'
        ));
    }

    /**
     * @Route("/admin/appelant/edit/{id}", name="edit-appelant", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $appelant = $em->getRepository(Appelant::class)->find($id);

        if(!$appelant){
            $this->addFlash('danger', "Oops, une erreur est survenue.");
            return $this->redirectToRoute('appelant');
        }

        $form = $this->createForm(AppelantType::class, $appelant);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($appelant);
            $em->flush();

            $this->addFlash('success', "L'appelant a bien été modifié.");
            return $this->redirectToRoute('appelant');
        }

        return $this->render('form.html.twig', array(
            'form' => $form->createView(),
            'titre' => "Modifier un appelant",
            'back_path' => 'appelant'
        ));
    }

    /**
     * @Route("/admin/appelant", name="appelant")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $appelants = $em->getRepository(Appelant::class)->findAll();

        return $this->render('admin/appelant/index.html.twig', array(
            'appelants' => $appelants
        ));
    }
}
