<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     */
    public function changePassword(Request $request){
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");

    }
}
