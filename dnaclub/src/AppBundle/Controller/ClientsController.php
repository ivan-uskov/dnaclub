<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ClientNote;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Client;

class ClientsController extends Controller
{
    /**
     * @Route("/clients", name="clientsList")
     */
    public function clientsListAction(Request $request)
    {
		$clients = $this->getDoctrine()->getRepository('AppBundle:Client')->findAll();
        return $this->render('clients/clients_list.html.twig', ['clients' => $clients]);
    }

	/**
	 * @Route("/new-client", name="createClient")
	 */
	public function createClientAction(Request $request)
	{
		if ($request->isMethod(Request::METHOD_GET))
		{
			return $this->render('clients/create_client.html.twig');
		}
		else if ($request->isMethod(Request::METHOD_POST))
		{
			$post = $request->request;
			$this->handlePost($post);
			return $this->redirectToRoute('clientsList');
		}
	}

	private function handlePost($post)
	{
		$em = $this->getDoctrine()->getManager();
		$client = new Client();
		$client->setLastName($post->get('last_name'));
		$client->setFirstName($post->get('first_name'));
		$client->setMiddleName($post->get('middle_name'));
		$client->setBirthday(new \DateTime($post->get('birthday')));
		$client->setCity($post->get('city'));
		$client->setIsSubscribed($post->get('isSubscribed') === "on" ? 1 : 0);
		$client->setIsSchoolLearner($post->get('isSchoolLearner') === "on" ? 1 : 0);
		$client->setIsOnlineLearner($post->get('isOnlineLearner') === "on" ? 1 : 0);
		$client->setPhone($post->get('phone'));
		$client->setEmail($post->get('email'));
		$client->setSubscriptionDate($post->get('subscriptionDate'));
		$noteStr = $post->get('notes');
		if ($noteStr !== "")
		{
			$note = new ClientNote();
			$note->setText($noteStr);
			$note->setClient($client);
			$em->persist($note);
			$client->addNote($note);
		}

		$em->persist($client);
		$em->flush();
	}
}