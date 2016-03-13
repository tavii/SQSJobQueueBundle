<?php
/**
 * Created by PhpStorm.
 * User: polidog
 * Date: 2016/03/13
 */

namespace Tavii\SQSJobQueueBundle;


final class SQSJobQueueEvents
{

    const QUEUE_RECEIVED = 'sqs_job_queue.queue_received';

    const QUEUE_SENT = "sqs_job_queue.queue_sent";

    const QUEUE_DELETED = "sqs_job_queue.queue_deleted";

    const JOB_EXECUTE = "sqs_job_queue.job_execute";

    const JOB_RAN = "sqs_job_queue.job_run";
}