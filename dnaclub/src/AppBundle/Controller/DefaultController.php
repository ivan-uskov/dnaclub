<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/login.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }

	public function clientAction(Request $request)
	{
		$client = new Client();

		$form = $this->createFormBuilder($cilent)
			-> add("name", "text")
			-> add("save", "submit")
			-> add("cancel", "submit")
			-> getForm();

		return $this->render("default/addClient.html.twig", array(
			"form" => $form->createView()
		));
	}

    /**
     * @Route("/logoff", name="logoff")
     */
    public function logoffAction(Request $request)
    {
        return $this->redirectToRoute('login');
    }
}
