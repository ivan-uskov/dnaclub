<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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

        $dates = $em->getRepository('AppBundle:MarketingReport')->getMonthsForSelect();
        $form = $this->createForm(new MonthSearchForm(), array('months' => ''), array('dates' => $dates));
        $form->handleRequest($request);

        $data = $form->getData();
        $date = $data["months"] ?: key($dates);
        $reportRows = $em->getRepository('AppBundle:MarketingReport')->findByDate($date);

        $updateReportForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('updateMarketingReport'))
            ->setMethod('POST')
            ->add('date', 'hidden', array('data' => $date))
            ->add('update', 'submit', array('label' => 'Обновить отчет'))
            ->getForm()
        ;
        $updateReportForm->handleRequest($request);

        return $this->render('reports/marketing_report.html.twig', ['reportRows' => $reportRows, 'form' => $form->createView(), 'updateReportForm' => $updateReportForm->createView()]);
    }

    /**
     * @Route("/marketing-report/update", name="updateMarketingReport")
     */
    public function updateMarketingReportAction(Request $request)
    {
        $form = $request->request->get('form');
        $date = $form['date'];
        if ($date)
        {
            $em = $this->getDoctrine()->getManager()->getConnection();
            $em->prepare("CALL fill_marketing_report('" . $date . "')")->execute();
        }

        return $this->redirectToRoute('marketingReport');
    }


}