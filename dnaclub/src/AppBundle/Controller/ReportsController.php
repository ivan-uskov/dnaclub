<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReportsController extends Controller
{
    /**
     * @Route("/marketing-report", name="marketingReport")
     */
    public function marketingReportAction(Request $request)
    {
        $post = $request->request;
        $date = $post->get('month') ?: '2015-12-01';

        $em = $this->getDoctrine()->getManager();
        $reportRows = $em->getRepository('AppBundle:MarketingReport')->findByDate($date);

        return $this->render('reports/marketing_report.html.twig', ['reportRows' => $reportRows]);
    }
}