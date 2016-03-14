<?php
namespace Tavii\SQSJobQueueBundle\Storage;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Tavii\SQSJobQueue\Queue\QueueName;
use Tavii\SQSJobQueue\Storage\EntityInterface;
use Tavii\SQSJobQueue\Storage\StorageInterface;
use Tavii\SQSJobQueueBundle\Entity\SqsWorker;


class DoctrineStorage implements StorageInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @param EntityManager $em
     * @param EntityRepository $repository
     */
    public function __construct(
        EntityManager $em,
        EntityRepository $repository
    )
    {
        $this->entityManager = $em;
        $this->repository = $repository;
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function find(QueueName $queueName, $server = null, $procId = null)
    {
        return $this->repository->findBy($this->createCriteria($queueName, $server, $procId));
    }

    /**
     * {@inheritdoc}
     */
    public function set(QueueName $queueName, $server, $procId)
    {
        $entity = new SqsWorker();
        $entity->setQueueName($queueName)
            ->setServer($server)
            ->setProcId($procId)
        ;
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

    }

    /**
     * {@inheritdoc}
     */
    public function get(QueueName $queueName, $server, $procId)
    {
        return $this->repository->findOneBy($this->createCriteria($queueName, $server, $procId));
    }

    /**
     * {@inheritdoc}
     */
    public function remove(QueueName $queueName, $server, $procId)
    {
        $entity = $this->get($queueName, $server, $procId);
        if ($entity instanceof EntityInterface) {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeForce(QueueName $queueName, $server)
    {
        /** @var SqsWorker $entity */
        foreach ($this->find($queueName, $server) as $entity) {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        }
    }


    /**
     * @param QueueName $queueName
     * @param string|null $server
     * @param string|null $procId
     * @return array
     */
    protected function createCriteria(QueueName $queueName, $server = null, $procId = null)
    {
        $criteria = array(
            'queue' => $queueName->getName(),
        );

        if (!empty($queueName->getPrefix())) {
            $criteria['prefix'] = $queueName->getPrefix();
        }

        if ($server !== null) {
            $criteria['server'] = $server;
        }

        if ($procId !== null) {
            $criteria['procId'] = $procId;
        }

        return $criteria;
    }

}