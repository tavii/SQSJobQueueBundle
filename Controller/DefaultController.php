<?php

namespace Tavii\SQSJobQueueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('TaviiSQSJobQueueBundle:Default:index.html.twig', array('name' => $name));
    }
}
