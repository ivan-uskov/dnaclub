<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ClientsController extends Controller
{
    /**
     * @Route("/clients", name="clientsList")
     */
    public function clientsListAction(Request $request)
    {
        return $this->render('clients/clients_list.html.twig');
    }
}