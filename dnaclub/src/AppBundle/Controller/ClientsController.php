<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\Reward;
use AppBundle\Entity\DiseaseHistory;
use AppBundle\Entity\Repository\RewardRepository;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ClientsController extends Controller
{
    /**
     * @Route("/clients", name="clientsList")
     */
    public function clientsListAction(Request $request)
    {
        $filters = array();
        $keyIsSubscribed = "checkbox_is_subscribed";
        $keyIsSchoolLearner = "checkbox_is_school_learner";
        $keyIsOnlineLearner = "checkbox_is_online_learner";

        if ($request->isMethod("post")) {
            $postKeys = $request->request->keys();
            $isSubscribed = in_array($keyIsSubscribed, $postKeys);
            $isSchoolLearner = in_array($keyIsSchoolLearner, $postKeys);
            $isOnlineLearner = in_array($keyIsOnlineLearner, $postKeys);

            if ($isSubscribed)
            {
                array_push($filters, $keyIsSubscribed);
            }
            if ($isSchoolLearner)
            {
                array_push($filters, $keyIsSchoolLearner);
            }
            if ($isOnlineLearner)
            {
                array_push($filters, $keyIsOnlineLearner);
            }
            $clients = $this->getDoctrine()->getRepository('AppBundle:Client')->getFilteredByCheckboxes($isSubscribed, $isSchoolLearner, $isOnlineLearner);
        }
        else
        {
            $clients = $this->getDoctrine()->getRepository('AppBundle:Client')->findBy(
                ['isDeleted' => 0],
                ['lastName' => "ASC"]
            );
        }

        return $this->render('clients/clients_list.html.twig', [
            'clients' => $clients,
            'filters' => $filters
        ]);
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
            $this->get('session')->getFlashBag()->add('success', 'Изменения успешно сохранены');
            return $this->redirectToRoute('editClient', ['clientId' => $clientId]);
        }
    }

    /**
     * @Route("/client/{clientId}/ajax/rewards", name="clientRewardsAjax")
     */
    public function clientRewardsAjaxAction(Request $request, $clientId)
    {
        $rewardsJson = [];
        $client = $this->getDoctrine()->getRepository("AppBundle:Client")->find($clientId);
        $rewardsRepository = $this->getDoctrine()->getRepository('AppBundle:Reward'); /** @var $rewardsRepository RewardRepository */
        $rewards = $rewardsRepository->findNotDeletedByClient($client, true); /** @var Reward $reward */

        foreach ($rewards as $reward)
        {
            $rewardsJson[] = [
                'id'        => $reward->getRewardId(),
                'sum'       => $reward->getSum(),
                'name'      => $reward->getName(),
                'remaining' => $reward->getRemainingSum()
            ];
        }

        return new JsonResponse(['rewards' => $rewardsJson]);
    }

    /**
     * @Route("/delete-client/{clientId}", name="deleteClient")
     */
    public function deleteClientAction(Request $request, $clientId)
    {
        $em = $this->getDoctrine()->getManager();
        /**
         * @var Client $client
         */
        $client = $em->getRepository("AppBundle:Client")->find($clientId);
        $flashBag = $this->get('session')->getFlashBag();
        if ($client->isAllowedToDelete())
        {
            $client->setIsDeleted(true);
            $em->persist($client);
            $em->flush();
            $flashBag->add('success', 'Данные о клиенте удалены');
        }
        else
        {
            $flashBag->add('error', 'Нельзя удалить клиента, т.к. с ним связаны денежные операции');
        }

        return new RedirectResponse($request->headers->get('referer'));
    }

    /**
     * @Route("/disease-history/{clientId}", name="diseaseHistory")
     */
    public function diseaseHistoryAction(Request $request, $clientId)
    {
        $client = $this->getDoctrine()->getRepository("AppBundle:Client")->find($clientId);
        $diseaseHistories = $this->getDoctrine()->getRepository("AppBundle:DiseaseHistory")->findBy(
            ['client' => $client],
            ['date' => 'DESC']
        );
        if ($request->isMethod(Request::METHOD_POST))
        {
            $this->handleDiseaseHistoryPost($request->request, $clientId);
            $diseaseHistories = $this->getDoctrine()->getRepository("AppBundle:DiseaseHistory")->findBy(
                ['client' => $client],
                ['date' => 'DESC']
            );
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
        $diseaseHistories = $this->getDoctrine()->getRepository("AppBundle:DiseaseHistory")
            ->findBy(['client' => $client]);
        $currentDiseaseHistory = $this->getDoctrine()->getRepository("AppBundle:DiseaseHistory")
            ->find($diseaseHistoryId);
        if ($request->isMethod(Request::METHOD_POST))
        {
            $this->handleDiseaseHistoryPost($request->request, $clientId, $diseaseHistoryId);
            return $this->redirectToRoute('diseaseHistory', ['clientId' => $clientId]);
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
        $em = $this->getDoctrine()->getManager();
        $client = $em->getRepository("AppBundle:Client")->find($clientId);
        $isNew = ($diseaseHistoryId === null);
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