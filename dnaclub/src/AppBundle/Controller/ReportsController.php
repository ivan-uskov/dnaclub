<?php

namespace AppBundle\Controller;

use AppBundle\config\SubscriptionType;
use AppBundle\Entity\Subscription;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use AppBundle\Entity\Client;
use AppBundle\Entity\MarketingReport;
use AppBundle\Form\MonthSearchForm;

class ReportsController extends Controller
{
    /**
     * @Route("/marketing-report", name="marketingReport")
     */
    public function marketingReportAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $defaultDate = (new \DateTime())->format('Y-m-01');
        $dates = $em->getRepository('AppBundle:MarketingReport')->getMonthsForSelect();
        $form = $this->createForm(new MonthSearchForm(), array('months' => $defaultDate), array('dates' => $dates));
        $form->handleRequest($request);

        $date = $form->getData()["months"];
        $reportRows = $em->getRepository('AppBundle:MarketingReport')->findByDate($date);

        $updateReportForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('updateMarketingReport'))
            ->setMethod('POST')
            ->add('date', 'hidden', array('data' => $date))
            ->add('update', 'submit', array('label' => 'Пересчитать отчет'))
            ->getForm()
        ;
        $updateReportForm->handleRequest($request);

        return $this->render('reports/marketing_report.html.twig', [
            'reportRows' => $reportRows,
            'form' => $form->createView(),
            'updateReportForm' => $updateReportForm->createView(),
            'contractPrice' => SubscriptionType::getPrice(SubscriptionType::CONTRACT),
            'maintenancePrice' => SubscriptionType::getPrice(SubscriptionType::MAINTENANCE),
            'isCurrentMonth' => ($defaultDate == $date)
        ]);
    }

    /**
     * @Route("/marketing-report/update", name="updateMarketingReport")
     */
    public function updateMarketingReportAction(Request $request)
    {
        $form = $request->request->get('form');
        $date = $form['date'];

        $flashBag = $this->get('session')->getFlashBag();
        if ($date)
        {
            try
            {
                $em = $this->getDoctrine()->getManager()->getConnection();
                $em->prepare("CALL fill_marketing_report('" . $date . "')")->execute();
                $dt = (new \DateTime($date))->format('d.m.Y');
                $flashBag->add('success', 'Данные отчета пересчитаны с ' . $dt);
            }
            catch(\Exception $e)
            {
                $flashBag->add('error', 'Данные не пересчитаны, произошла ошибка!');
            }
        }

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
        //return $this->redirectToRoute('marketingReport');
    }

    /**
     * @Route("/marketing-report/save-subscriptions", name="marketingReportSaveSubscriptions")
     */
    public function saveSubscriptionsAction(Request $request)
    {
        $contractArr = json_decode($request->request->get('contract'), true);
        $maintenanceArr = json_decode($request->request->get('maintenance'), true);

        if (empty($contractArr) && empty($maintenanceArr))
        {
            return $this->redirectToRoute('marketingReport');
        }

        $flashBag = $this->get('session')->getFlashBag();
        try
        {
            $this->insertSubscriptions($contractArr, SubscriptionType::CONTRACT);
            $this->insertSubscriptions($maintenanceArr, SubscriptionType::MAINTENANCE);
            $flashBag->add('success', 'Изменения были успешно сохранены');
        }
        catch (\Exception $e)
        {
            $flashBag->add('error', 'Изменения не сохранены, произошла ошибка!');
        }

        return $this->redirectToRoute('marketingReport');
    }

    private function insertSubscriptions($subscriptions, $type)
    {
        $em = $this->getDoctrine()->getManager();

        foreach ($subscriptions as $clientId => $count)
        {
            $client = $em->getRepository("AppBundle:Client")->find($clientId);
            $subscription = new Subscription();
            $subscription->setClient($client)
                ->setType($type)
                ->setCount($count)
                ->setSum($count * SubscriptionType::getPrice($type))
                ->setDate(new \DateTime());

            $em->persist($subscription);
        }

        $em->flush();
    }
}