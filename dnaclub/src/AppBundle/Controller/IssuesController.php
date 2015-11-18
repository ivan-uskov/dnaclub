<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IssuesController extends Controller
{
    /**
     * @Route("/issues", name="issuesList")
     */
    public function issuesListAction(Request $request)
    {
        return $this->render('issues/issues_list.html.twig');
    }
}