<?
namespace AppBundle\Controller;

use AppBundle\Entity\Reward;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class RewardsController extends Controller
{
    /**
     * @Route("/rewards", name="rewardsList")
     */
    public function rewardsListAction(Request $request)
    {
        return $this->rewardsListImpl();
    }

    /**
     * @Route("/clients-rewards/{clientId}", name="clientsRewards")
     */
    public function clientsRewardsListAction(Request $request, $clientId)
    {
        return $this->rewardsListImpl($clientId);
    }

    /**
     * @Route("/rewards/new", name="createReward")
     */
    public function createRewardAction(Request $request)
    {
        return $this->createRewardImpl($request);
    }

    /**
     * @Route("/clients-rewards/{clientId}/new", name="createClientsReward")
     */
    public function createClientsRewardAction(Request $request, $clientId)
    {
        return $this->createRewardImpl($request, $clientId);
    }

    /**
     * @Route("/rewards/edit/{rewardId}", name="editReward")
     */
    public function editRewardAction(Request $request, $rewardId)
    {
        return $this->editRewardImpl($request, $rewardId);
    }

    /**
     * @Route("/clients-rewards/{clientId}/edit/{rewardId}", name="editClientsReward")
     */
    public function editClientsRewardAction(Request $request, $clientId, $rewardId)
    {
        return $this->editRewardImpl($request, $rewardId, $clientId);
    }

    /**
     * @Route("/rewards/delete/{rewardId}", name="deleteReward")
     */
    public function deleteRewardAction(Request $request, $rewardId)
    {
        return $this->deleteRewardImpl($rewardId);
    }

    /**
     * @Route("/clients-rewards/{clientId}/delete/{rewardId}", name="deleteClientsReward")
     */
    public function deleteClientsRewardAction(Request $request, $clientId, $rewardId)
    {
        return $this->deleteRewardImpl($rewardId, $clientId);
    }

    private function handleRewardPost(ParameterBag $post, $rewardId = null)
    {
        $isNew = ($rewardId === null);
        $reward = $isNew
            ? new Reward()
            : $this->getDoctrine()->getRepository("AppBundle:Reward")->find($rewardId);
        $client = $this->getDoctrine()->getRepository("AppBundle:Client")->find($post->get('client'));
        $reward->saveFromPost($post, $this->getDoctrine()->getManager(), $client);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function rewardsListImpl($clientId = null)
    {
        $client = null;
        if ($clientId != null)
        {
            $client = $this->getDoctrine()->getRepository("AppBundle:Client")->find($clientId);
        }
        $isClientPredefined = (($clientId != null) && ($client != null));
        if ($isClientPredefined)
        {
            $rewards = $this->getDoctrine()->getRepository('AppBundle:Reward')->findNotDeletedByClient($client);
            $templateMode = 'clientsRewards';
        }
        else
        {
            $rewards = $this->getDoctrine()->getRepository('AppBundle:Reward')->findNotDeleted();
            $templateMode = 'rewards';
        }

        return $this->render('payment/rewards_list.html.twig', [
            'rewards' => $rewards,
            'client' => $client,
            'mode' => $templateMode
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    private function createRewardImpl(Request $request, $clientId = null)
    {
        $client = null;
        if ($clientId != null)
        {
            $client = $this->getDoctrine()->getRepository("AppBundle:Client")->find($clientId);
        }
        $isClientPredefined = (($clientId != null) && ($client != null));
        if ($isClientPredefined)
        {
            $templateMode = 'editClientsReward';
        }
        else
        {
            $templateMode = 'editReward';
        }

        if ($request->isMethod(Request::METHOD_POST))
        {
            $this->handleRewardPost($request->request);

            if ($isClientPredefined)
            {
                return $this->redirectToRoute('clientsRewards', ['clientId' => $client->getClientId()]);
            }
            else
            {
                return $this->redirectToRoute('rewardsList');
            }
        }
        else
        {
            if ($isClientPredefined)
            {
                $clients = [$client];
            }
            else
            {
                $clients = $this->getDoctrine()->getRepository("AppBundle:Client")->findBy(
                    ['isDeleted' => 0],
                    ['lastName' => 'ASC']
                );
            }

            return $this->render('payment/reward_form.html.twig', [
                'clients' => $clients,
                'mode' => $templateMode,
                'isNew' => true
            ]);
        }
    }

    /**
     * @param $rewardId
     * @param $clientId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function deleteRewardImpl($rewardId, $clientId = null)
    {
        $em = $this->getDoctrine()->getManager();
        $reward = $this->getDoctrine()->getRepository("AppBundle:Reward")->find($rewardId);
        $reward->setIsDeleted(true);
        $em->persist($reward);
        $em->flush();

        if ($clientId == null)
        {
            return $this->redirectToRoute('rewardsList');
        }
        else
        {
            return $this->redirectToRoute('clientsRewards', ['clientId' => $clientId]);
        }
    }

    /**
     * @param Request $request
     * @param $clientId
     * @param $rewardId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    private function editRewardImpl(Request $request, $rewardId, $clientId = null)
    {
        $client = null;
        if ($clientId != null)
        {
            $client = $this->getDoctrine()->getRepository("AppBundle:Client")->find($clientId);
        }
        $isClientPredefined = (($clientId != null) && ($client != null));
        if ($isClientPredefined)
        {
            $templateMode = 'editClientsReward';
        }
        else
        {
            $templateMode = 'editReward';
        }

        $reward = $this->getDoctrine()->getRepository("AppBundle:Reward")->find($rewardId);
        if ($request->isMethod(Request::METHOD_POST))
        {
            $this->handleRewardPost($request->request, $rewardId);

            if ($isClientPredefined)
            {
                return $this->redirectToRoute('clientsRewards', ['clientId' => $client->getClientId()]);
            }
            else
            {
                return $this->redirectToRoute('rewardsList');
            }
        }
        else
        {
            if ($isClientPredefined)
            {
                $clients = [$client];
            }
            else
            {
                $clients = $this->getDoctrine()->getRepository("AppBundle:Client")->findBy(
                    ['isDeleted' => 0],
                    ['lastName' => 'ASC']
                );
            }

            return $this->render('payment/reward_form.html.twig', [
                'clients' => $clients,
                'isNew' => false,
                'mode' => $templateMode,
                'reward' => $reward
            ]);
        }
    }
}