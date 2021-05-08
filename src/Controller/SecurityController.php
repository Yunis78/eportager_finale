<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Security;

class SecurityController
{
    /**
     * @var Environment
     */
    private $twig;
    
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * @var EntityManagerInterface
     */
    private $router;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var Security
     */
    private $security;

    public function __construct(Environment $twig, EntityManagerInterface $em, RouterInterface $router, FormFactoryInterface $formFactory, Security $security)
    {
        $this->twig = $twig;
        $this->em = $em;
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->security = $security;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        if ($this->security->getUser()) {
            return new RedirectResponse(
                $this->router->generate(
                    'homepage',
                )
            );
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return new Response($this->twig->render('components/pages/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]));
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
