<?
namespace AppBundle\Controller;

use AppBundle\Entity\Reward;
use AppBundle\utils\DateUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\MonthSearchForm;
use AppBundle\Form\RewardForm;

class RewardsController extends Controller
{
    /**
     * @Route("/rewards", name="rewardsList")
     */
    public function rewardsListAction(Request $request)
    {
        return $this->rewardsListImpl($request);
    }

    /**
     * @Route("/clients-rewards/{clientId}", name="clientsRewards")
     */
    public function clientsRewardsListAction(Request $request, $clientId)
    {
        return $this->rewardsListImpl($request, $clientId);
    }

    /**
     * @Route("/rewards/edit/{rewardId}", name="editReward")
     */
    public function editRewardAction(Request $request, $rewardId)
    {
        $clientId = $request->query->get('clientId');

        $em = $this->getDoctrine()->getManager();
        $reward = $em->getRepository('AppBundle:Reward')->find($rewardId);

        $rewardForm = $this->createForm(new RewardForm(), $reward, array('isNew' => false, 'em' => $em));
        $rewardForm->handleRequest($request);

        if ($rewardForm->isSubmitted() && $rewardForm->isValid())
        {
            $reward = $rewardForm->getData();
            $reward->actualizeRemainingSum();
            $em->merge($reward);
            $em->flush();

            if ($clientId)
            {
                return $this->redirectToRoute('clientsRewards', ['clientId' => $clientId]);
            }
            else
            {
                return $this->redirectToRoute('rewardsList');
            }
        }

        return $this->render('payment/reward_form.html.twig', ['rewardForm' => $rewardForm->createView(), 'isNew' => false]);
    }

    /**
     * @Route("/rewards/delete/{rewardId}", name="deleteReward")
     */
    public function deleteRewardAction(Request $request, $rewardId)
    {
        $em = $this->getDoctrine()->getManager();
        $reward = $em->getRepository("AppBundle:Reward")->find($rewardId);
        $reward->setIsDeleted(true);
        $em->persist($reward);
        $em->flush();
        return new RedirectResponse($request->headers->get('referer'));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function rewardsListImpl(Request $request, $clientId = null)
    {
        $em = $this->getDoctrine()->getManager();
        $rewardRepository = $em->getRepository('AppBundle:Reward');

        $defaultDate = (new \DateTime())->format('Y-m-01');
        $dates = $rewardRepository->getMonthsForSelect();
        $form = $this->createForm(new MonthSearchForm(), array('months' => $defaultDate), array('dates' => $dates));
        $form->handleRequest($request);

        $date = $form->getData()["months"];

        $client = null;
        if ($clientId != null)
        {
            $client = $this->getDoctrine()->getRepository("AppBundle:Client")->find($clientId);
        }

        $isNew = true;

        $reward = new Reward();
        $reward->setClient($client);
        $reward->setDate(DateUtils::getFirstDayOfThisMonth());


        $rewardForm = $this->createForm(new RewardForm(), $reward, array('isNew' => true, 'em' => $em));
        $rewardForm->handleRequest($request);

        if ($rewardForm->isSubmitted() && $rewardForm->isValid())
        {
            $reward->actualizeRemainingSum();
            $em->persist($reward);
            $em->flush();
            return new RedirectResponse($request->headers->get('referer'));
        }
        if ($rewardForm->isSubmitted() && !$rewardForm->isValid())
        {
            $isNew = false;
        }

        $isClientPredefined = (($clientId != null) && ($client != null));
        $templateMode = $isClientPredefined ? 'clientsRewards' : 'rewards';
        $rewards = $isClientPredefined
            ? $rewardRepository->findNotDeletedByClient($client)
            : $rewardRepository->findByMonth($date);

        return $this->render('payment/rewards_list.html.twig', [
            'rewards' => $rewards,
            'mode'    => $templateMode,
            'form'    => $form->createView(),
            'client'  => $client,
            'isNew'   => $isNew,
            'rewardForm' => $rewardForm->createView()
        ]);
    }
}