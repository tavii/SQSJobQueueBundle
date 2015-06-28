<?php
namespace Tavii\SQSJobQueueBundle\Storage;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Tavii\SQSJobQueue\Storage\EntityInterface;
use Tavii\SQSJobQueue\Storage\StorageInterface;
use Tavii\SQSJobQueueBundle\Entity\SqsWorker;
use Tavii\SQSJobQueueBundle\Exception\EntityNotFoundException;

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

    public function all()
    {
        return $this->repository->findAll();
    }

    public function find($queue, $server = null, $procId = null)
    {
        return $this->repository->findBy($this->createCriteria($queue, $server, $procId));
    }

    /**
     * {@inheritdoc}
     */
    public function set($queue, $server, $procId)
    {
        $entity = new SqsWorker();
        $entity->setQueue($queue)
            ->setServer($server)
            ->setProcId($procId)
        ;
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

    }

    /**
     * {@inheritdoc}
     */
    public function get($queue, $server, $procId)
    {
        return $this->repository->findOneBy($this->createCriteria($queue, $server, $procId));
    }

    /**
     * {@inheritdoc}
     */
    public function remove($queue, $server, $procId)
    {
        $entity = $this->get($queue, $server, $procId);
        if ($entity instanceof EntityInterface) {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeForce($queue, $server)
    {
        /** @var SqsWorker $entity */
        foreach ($this->find($queue, $server) as $entity) {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        }
    }



    /**
     * {@inheritdoc}
     */
    public function create(array $params = array())
    {
        // TODO: Implement create() method.
    }

    /**
     * @param string $queue
     * @param string $server
     * @param int $procId
     * @return array
     */
    protected function createCriteria($queue, $server = null, $procId = null)
    {
        $criteria = array(
            'queue' => $queue
        );

        if ($server !== null) {
            $criteria['server'] = $server;
        }

        if ($procId !== null) {
            $criteria['procId'] = $procId;
        }

        return $criteria;
    }

}