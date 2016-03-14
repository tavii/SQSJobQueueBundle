<?php
/**
 * Created by PhpStorm.
 * User: polidog
 * Date: 2016/03/14
 */

namespace Tavii\SQSJobQueueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tavii\SQSJobQueueBundle\Storage\DoctrineStorage;

class DefaultController extends Controller
{
    public function indexAction()
    {
        /** @var DoctrineStorage $storage */
        $storage = $this->get('sqs_job_queue.storage.doctrine');

        /** @var array $workers */
        $workers = $storage->all();

        return $this->render('TaviiSQSJobQueueBundle:Default:index.html.twig', [
            'workers' => $workers
        ]);
    }
}