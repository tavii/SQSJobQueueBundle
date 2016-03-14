<?php
/**
 * Created by PhpStorm.
 * User: polidog
 * Date: 2016/03/14
 */

namespace Tavii\SQSJobQueueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tavii\SQSJobQueueBundle\Storage\DoctrineStorage;

/**
 * Class DefaultController
 */
class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sqslistAction()
    {
        $prefix = $this->getParameter('sqs_job_queue.prefix');
        $client = $this->get('sqs_job_queue.client');
        $results = $client->listQueues(array(
            'QueueNamePrefix' => $prefix,
        ));

        return $this->render('TaviiSQSJobQueueBundle:Default:sqslist.html.twig', [
            'queueUrls' => $results['QueueUrls'],
            'prefix' => $prefix
        ]);


    }
}