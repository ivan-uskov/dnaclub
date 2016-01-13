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

        return $this->render('reports/marketing_report.html.twig', ['reportRows' => $reportRows, 'form' => $form->createView()]);
    }
}