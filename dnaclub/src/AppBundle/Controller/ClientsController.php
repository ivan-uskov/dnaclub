<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ClientNote;
use Iterator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Client;

class ClientsController extends Controller
{
    /**
     * @Route("/clients", name="clientsList")
     */
    public function clientsListAction(Request $request)
    {
		$clients = $this->getDoctrine()->getRepository('AppBundle:Client')->findBy(['isDeleted' => 0]);
        return $this->render('clients/clients_list.html.twig', ['clients' => $clients]);
    }

	/**
	 * @Route("/new-client", name="createClient")
	 */
	public function createClientAction(Request $request)
	{
		if ($request->isMethod(Request::METHOD_POST))
		{
			$this->handlePost($request->request);
			return $this->redirectToRoute('clientsList');
		}
		else
		{
			return $this->render('clients/client_form.html.twig', ['isNew' => true]);
		}
	}

	/**
	 * @Route("/edit-client/{clientId}", name="editClient")
	 */
	public function editClientAction(Request $request, $clientId)
	{
		if ($request->isMethod(Request::METHOD_GET))
		{
			$client = $this->getDoctrine()->getRepository("AppBundle:Client")->find($clientId);
			$notes = $client->getNotes()->toArray();
			$clientNotes = [];
			foreach ($notes as $note)
			{
				array_push($clientNotes, stream_get_contents($note->getText()));
			}
			return $this->render('clients/client_form.html.twig', ['isNew' => false, 'client' => $client, 'clientNotes' => $clientNotes]);
		}
		else
		{
			$this->handlePost($request->request, false, $clientId);
			return $this->redirectToRoute('clientsList');
		}
	}

	/**
	 * @Route("/delete-client/{clientId}", name="deleteClient")
	 */
	public function deleteClientAction(Request $request, $clientId)
	{
		$em = $this->getDoctrine()->getManager();
		$client = $this->getDoctrine()->getRepository("AppBundle:Client")->find($clientId);
		$client->setIsDeleted(true);
		$em->persist($client);
		$em->flush();
		return $this->redirectToRoute('clientsList');
	}

	private function handlePost(ParameterBag $post, $isNew = true, $clientId = null)
	{
		$client = $isNew
			? new Client()
			: $client = $this->getDoctrine()->getRepository("AppBundle:Client")->find($clientId);
		$client->saveFromPost($post, $this->getDoctrine()->getManager());
	}
}