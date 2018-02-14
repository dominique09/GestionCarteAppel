<?php

namespace App\Controller;

use App\Entity\Division;
use App\Entity\Role;
use App\Form\DivisionType;
use App\Form\RoleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    /**
     * @Route("/admin/role/create", name="create-role")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function create(Request $request){
        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($role);
            $em->flush();

            $this->addFlash('success', "La role a bien été créée.");
            return $this->redirectToRoute('role');
        }

        return $this->render('form.html.twig', array(
            'form' => $form->createView(),
            'titre' => "Créer un role",
            'back_path' => 'role'
        ));
    }

    /**
     * @Route("/admin/role/edit/{id}", name="edit-role", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $role = $em->getRepository(Role::class)->find($id);

        if(!$role){
            $this->addFlash('danger', "Oops, une erreur est survenue.");
            return $this->redirectToRoute('role');
        }

        $form = $this->createForm(RoleType::class, $role);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($role);
            $em->flush();

            $this->addFlash('success', "Le role a bien été modifié.");
            return $this->redirectToRoute('role');
        }

        return $this->render('form.html.twig', array(
            'form' => $form->createView(),
            'titre' => "Créer un role",
            'back_path' => 'role'
        ));
    }

    /**
     * @Route("/admin/role", name="role")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $roles = $em->getRepository(Role::class)->findAll();

        return $this->render('admin/role/index.html.twig', array(
            'roles' => $roles
        ));
    }
}
