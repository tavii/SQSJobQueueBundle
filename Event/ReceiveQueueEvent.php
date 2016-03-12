<?php
namespace Tavii\SQSJobQueueBundle\Event;
use Symfony\Component\EventDispatcher\Event;
use Tavii\SQSJobQueue\Message\MessageInterface;


/**
 * Created by PhpStorm.
 * User: polidog
 * Date: 2016/03/13
 */
class ReceiveQueueEvent extends Event
{
    /**
     * @var MessageInterface
     */
    private $message;

    /**
     * QueueEvent constructor.
     * @param MessageInterface $message
     */
    public function __construct(MessageInterface $message)
    {
        $this->message = $message;
    }

    /**
     * @return MessageInterface
     */
    public function getMessage()
    {
        return $this->message;
    }

}