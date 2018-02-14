<?php

namespace App\Controller;

use App\Entity\Intervenant;
use App\Entity\User;
use App\Form\UserEditType;
use App\Form\UserCreateType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends Controller
{
    /**
     * @Route("/admin/user", name="user")
     */
    public function index()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('/admin/user/index.html.twig',
            ['users' => $users]);
    }

    /**
     * @Route("/admin/user/create", name="create-user")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function createAction(Request $request, UserPasswordEncoderInterface $passwordEncoder){

        $user = new User();
        $form = $this->createForm(UserCreateType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'usager {$user->getUsername()} a été ajouté.");

            return $this->redirectToRoute('user');
        }


        return $this->render('/admin/user/form.html.twig',
            ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/user/edit/{id}", name="edit-user", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function editAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);

        if(!$user){
            $this->addFlash('danger', "Oops une erreur est survenue.");
            return $this->redirectToRoute('user');
        }

        $form = $this->createForm(UserEditType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'usager {$user->getUsername()} a été modifié.");
        }

        return $this->render('/admin/user/edit.html.twig',
            ['form' => $form->createView(),
                'user' => $user]);
    }
}
