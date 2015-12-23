<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ClientNote;
use AppBundle\Entity\DiseaseHistory;
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
			$this->handleClientPost($request->request);
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
			$this->handleClientPost($request->request, false, $clientId);
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

	/**
	 * @Route("/disease-history/{clientId}", name="diseaseHistory")
	 */
	public function diseaseHistoryAction(Request $request, $clientId)
	{
		$client = $this->getDoctrine()->getRepository("AppBundle:Client")->find($clientId);
		$diseaseHistories = $this->getDoctrine()->getRepository("AppBundle:DiseaseHistory")->findBy(['client' => $client]);
		if ($request->isMethod(Request::METHOD_POST))
		{
			$this->handleDiseaseHistoryPost($request->request, $clientId);
			$diseaseHistories = $this->getDoctrine()->getRepository("AppBundle:DiseaseHistory")->findBy(['client' => $client]);
		}
		return $this->render('clients/disease_history.html.twig', [
				'client' => $client,
				'diseaseHistories' => $diseaseHistories,
				'isNew' => true
		]);
	}

	/**
	 * @Route("/disease-history/{clientId}/edit/{diseaseHistoryId}", name="editDiseaseHistory")
	 */
	public function editDiseaseHistoryAction(Request $request, $clientId, $diseaseHistoryId)
	{
		$client = $this->getDoctrine()->getRepository("AppBundle:Client")->find($clientId);
		$diseaseHistories = $this->getDoctrine()->getRepository("AppBundle:DiseaseHistory")->findBy(['client' => $client]);
		$currentDiseaseHistory = $this->getDoctrine()->getRepository("AppBundle:DiseaseHistory")->find($diseaseHistoryId);
		if ($request->isMethod(Request::METHOD_POST))
		{
			$this->handleDiseaseHistoryPost($request->request, $clientId, $diseaseHistoryId);
			$diseaseHistories = $this->getDoctrine()->getRepository("AppBundle:DiseaseHistory")->findBy(['client' => $client]);
		}
		return $this->render('clients/disease_history.html.twig', [
				'client' => $client,
				'diseaseHistories' => $diseaseHistories,
				'currentDiseaseHistory' => $currentDiseaseHistory,
				'isNew' => false
		]);
	}

	/**
	 * @Route("/delete-disease-history/{diseaseHistoryId}", name="deleteDiseaseHistory")
	 */
	public function deleteDiseaseHistoryAction(Request $request, $diseaseHistoryId)
	{
		$diseaseHistory = $this->getDoctrine()->getRepository("AppBundle:DiseaseHistory")->find($diseaseHistoryId);
		$clientId = $diseaseHistory->getClient()->getClientId();
		$em = $this->getDoctrine()->getManager();
		$em->remove($diseaseHistory);
		$em->flush();
		return $this->redirectToRoute('diseaseHistory', ['clientId' => $clientId]);
	}

	private function handleClientPost(ParameterBag $post, $isNew = true, $clientId = null)
	{
		$client = $isNew
			? new Client()
			: $this->getDoctrine()->getRepository("AppBundle:Client")->find($clientId);
		$client->saveFromPost($post, $this->getDoctrine()->getManager());
	}

	private function handleDiseaseHistoryPost(ParameterBag $post, $clientId = null, $diseaseHistoryId = null)
	{
		$client = $this->getDoctrine()->getRepository("AppBundle:Client")->find($clientId);
		$em = $this->getDoctrine()->getManager();
		$isNew = $diseaseHistoryId === null;
		if ($isNew)
		{
			$diseaseHistory = new DiseaseHistory();
		}
		else
		{
			$diseaseHistory = $this->getDoctrine()->getRepository("AppBundle:DiseaseHistory")->find($diseaseHistoryId);
		}
		$diseaseHistory->saveFromPost($post, $em, $client, $isNew);
		return $this->redirectToRoute('editClient', ['clientId' => $clientId]);
	}
}