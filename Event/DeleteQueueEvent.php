<?php
/**
 * Created by PhpStorm.
 * User: polidog
 * Date: 2016/03/13
 */

namespace Tavii\SQSJobQueueBundle\Event;


use Symfony\Component\EventDispatcher\Event;
use Tavii\SQSJobQueue\Message\MessageInterface;

class DeleteQueueEvent extends Event
{
    /**
     * @var MessageInterface
     */
    private $message;

    /**
     * @var boolean
     */
    private $deleted;

    /**
     * DeleteQueueEvent constructor.
     * @param MessageInterface $message
     * @param bool $deleted
     */
    public function __construct(MessageInterface $message, $deleted)
    {
        $this->message = $message;
        $this->deleted = $deleted;
    }

    /**
     * @return MessageInterface
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return boolean
     */
    public function isDeleted()
    {
        return $this->deleted;
    }



}