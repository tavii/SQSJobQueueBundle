<?php
namespace Tavii\SQSJobQueueBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Tavii\SQSJobQueue\Storage\EntityInterface;

/**
 * Class SqsJobQueue
 * @package Tavii\SQSJobQueueBundle\Entity
 *
 * @ORM\Entity(repositoryClass="Tavii\SQSJobQueueBundle\Repository\SqsWorkerRepository")
 * @ORM\Table(name="sqs_workers")
 * @ORM\HasLifecycleCallbacks()
 */
class SqsWorker implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint", name="id")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", name="server", nullable=false)
     */
    protected $server;

    /**
     * @ORM\Column(type="string", name="queue", nullable=false)
     */
    protected $queue;

    /**
     * @ORM\Column(type="integer", name="proc_id", nullable=false)
     */
    protected $procId;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", name="updated_at")
     */
    protected $updatedAt;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param $server
     * @return $this
     */
    public function setServer($server)
    {
        $this->server = $server;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * @param $queue
     * @return $this
     */
    public function setQueue($queue)
    {
        $this->queue = $queue;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProcId()
    {
        return $this->procId;
    }

    /**
     * @param int $procId
     * @return $this
     */
    public function setProcId($procId)
    {
        $this->procId = $procId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function createDate()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function updateDate()
    {
        $this->updatedAt = new \DateTime();
    }

}