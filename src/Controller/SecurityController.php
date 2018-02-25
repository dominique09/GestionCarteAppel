<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountEditPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authUtils)
    {
        //$this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");

        $error = $authUtils->getLastAuthenticationError();

        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }

    /**
     * @Route("/account/changePassword", name="change-password")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder){
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");

        //$user = new User();
        $user = $this->getUser();
        $form = $this->createForm(AccountEditPasswordType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($passwordEncoder->isPasswordValid($user, $user->getPreviousPassword())){
                $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()));

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->addFlash('success', "Le mot de passe a bien été modifié.");

                return $this->redirectToRoute('repartition');
            } else {
                $this->addFlash('danger', "Le mot de passe précédent n'est pas valide.");
            }
        }

        return $this->render('/form.html.twig', [
            'titre' => "Modification du mot de passe",
            'form' => $form->createView()
        ]);
    }
}
