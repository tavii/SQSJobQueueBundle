# SQSJobQueueBundle
[![Build Status](https://travis-ci.org/tavii/SQSJobQueueBundle.svg)](https://travis-ci.org/tavii/SQSJobQueueBundle)
[![Coverage Status](https://coveralls.io/repos/tavii/SQSJobQueueBundle/badge.svg?branch=master)](https://coveralls.io/r/tavii/SQSJobQueueBundle?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tavii/SQSJobQueueBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tavii/SQSJobQueueBundle/?branch=master)

[SQSJobQueue](https://github.com/tavii/SQSJobQueue)をSymfony2で扱う為のバンドル


## Requirements

- PHP5.3+
- Symfony2.3+

## Installation

### 1: composerでインストールをする。

```
$ composer require tavii/sqs-job-queue-bundle:0.0.1@dev
```

### 2: Appkernelに追加する。

```
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Tavii\SQSJobQueueBunblde\SQSJobQueueBundle()
        );

        // ...
    }

    // ...
}
```

### 3: config.ymlの設定

```
tavii_sqs_job_queue:
    aws:
        key: %aws_api_key%
        secret: %aws_api_secret%
        region: %aws_api_region%
```


## Usage

### 1: setup databsae table

```
$ app/console sqs_job_queue:storage-init
```

### 2: create job


```
<?php
// src/AcmeBundle/Job/TestJob.php
namespace AcmeBundle;

use Tavii\SQSJobBundle\Job\Job;

class TestJob extends Job
{
    public function run()
    {
      echo "run test job!!";
    }

    public function getName()
    {
      return "test_job"; // queue-url path;
    }
}
```


### 3: register job


```
<?php 
...

$job = new TestJob();
$this->getContainer()->get('sqs_job_queue.queue')->push($job);
```




## TODO
- ストレージがDoctrineしか使えない問題の解消
- 管理画面の提供
