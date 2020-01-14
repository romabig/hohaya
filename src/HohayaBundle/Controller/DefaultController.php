<?php

namespace HohayaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('frontend.template.html.twig');
    }

    /**
     * Gère la page d'administration dashBoard
     * @return '@Hohaya/Default/dashboard.html.twig'
     */
    public function adminDashboardAction()
    {
        return $this->render('@Hohaya/Default/dashboard.html.twig');
    }

    public function menuAppAction(){

        return $this->render('@Hohaya/Default/menu_app.html.twig');
    }

}

