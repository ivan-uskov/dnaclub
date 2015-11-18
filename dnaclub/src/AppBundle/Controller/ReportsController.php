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
        return $this->render('reports/marketing_report.html.twig');
    }
}