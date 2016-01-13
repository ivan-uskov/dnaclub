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
        $rewards = $this->getDoctrine()->getRepository('AppBundle:Reward')->findBy(['isDeleted' => 0]);

        return $this->render('payment/rewards_list.html.twig', ['rewards' => $rewards]);
    }

    /**
     * @Route("/rewards/new", name="createReward")
     */
    public function createRewardAction(Request $request)
    {
        if ($request->isMethod(Request::METHOD_POST))
        {
            $this->handleRewardPost($request->request);

            return $this->redirectToRoute('rewardsList');
        }
        else
        {
            $clients = $this->getDoctrine()->getRepository("AppBundle:Client")->findBy(
                ['isDeleted' => 0],
                ['lastName' => 'ASC']
            );

            return $this->render('payment/reward_form.html.twig', [
                'clients' => $clients,
                'isNew' => true
            ]);
        }
    }

    /**
     * @Route("/rewards/edit/{rewardId}", name="editReward")
     */
    public function editRewardAction(Request $request, $rewardId)
    {
        $reward = $this->getDoctrine()->getRepository("AppBundle:Reward")->find($rewardId);
        if ($request->isMethod(Request::METHOD_POST))
        {
            $this->handleRewardPost($request->request, $rewardId);

            return $this->redirectToRoute('rewardsList');
        }
        else
        {
            $clients = $this->getDoctrine()->getRepository("AppBundle:Client")->findBy(
                ['isDeleted' => 0],
                ['lastName' => 'ASC']
            );

            return $this->render('payment/reward_form.html.twig', [
                'clients' => $clients,
                'isNew' => false,
                'reward' => $reward
            ]);
        }
    }

    /**
     * @Route("/rewards/delete/{rewardId}", name="deleteReward")
     */
    public function deleteRewardAction(Request $request, $rewardId)
    {
        $em = $this->getDoctrine()->getManager();
        $reward = $this->getDoctrine()->getRepository("AppBundle:Reward")->find($rewardId);
        $reward->setIsDeleted(true);
        $em->persist($reward);
        $em->flush();

        return $this->redirectToRoute('rewardsList');
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
}