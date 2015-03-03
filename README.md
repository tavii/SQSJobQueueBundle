# SQSJobQueueBundle
[![Build Status](https://travis-ci.org/tavii/SQSJobQueueBundle.svg)](https://travis-ci.org/tavii/SQSJobQueueBundle)
[![Coverage Status](https://coveralls.io/repos/tavii/SQSJobQueueBundle/badge.svg?branch=master)](https://coveralls.io/r/tavii/SQSJobQueueBundle?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tavii/SQSJobQueueBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tavii/SQSJobQueueBundle/?branch=master)

[SQSJobQueue](https://github.com/tavii/SQSJobQueue)をSymfony2で扱う為のバンドル。  
作った背景としては、Amaazon SQSを[BCCResqueBundle](https://github.com/michelsalib/BCCResqueBundle)と同じような感じで、Jobを利用した処理ができるということを目的につくりました。

## Requirements

- PHP5.3+
- Symfony2.3+

## Installation

### 1: composer installをする

```
$ composer require tavii/sqs-job-queue-bundle:0.0.3
```

### 2: AppKernelにSQSJobQueueBundleを登録する

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

### 3: config.ymlに設定を追加する

```
tavii_sqs_job_queue:
    aws:
        key: %aws_api_key%
        secret: %aws_api_secret%
        region: %aws_api_region%
```


## Usage

### 1: setup databsae table

ワーカーの情報をDBに記録するために、テーブルを生成します。  
現状は、`sqs_workers`というテーブルが作成されるようになっています。

```
$ app/console sqs_job_queue:storage-init
```

### 2: Amazon SQSにキューを登録する

amazon sqs側にqueueを登録します。  
AWSの管理画面から作成する事も出来ますが、コマンドも用意しました。
今回は`test`というキューを登録します。

```
$ app/console sqs_job_queue:queue-create test
```

### 3: Jobクラスを作成する

処理を担当するJobクラスを作成します。  
今回はメールを送るJobクラスを作成します。

`Tavii\SQSJobQueue\Job`クラス、または、`Tavii\SQSJobQueueBundle\ContainerAwareJob`クラスを継承したクラスを作ります。  
二つのクラスの違いはDIコンテナが使えるか使えないかの違いです。  
`Tavii\SQSJobQueueBundle\ContainerAwareJob`を利用すればDIコンテナを使うことが出来ます。

また、必ず`処理が成功した場合`は`true`を返すようにしてください。


```
<?php
// src/AppBundle/Job/SendMailJob.php

namespace AppBundle\Job;


use Tavii\SQSJobQueueBundle\ContainerAwareJob;

class SendMailJob extends ContainerAwareJob
{

    public function getName()
    {
        return 'test'; // ここをキュー名と一致させること
    }

    public function run()
    {
        $message = \Swift_Message::newInstance()
            ->setFrom('hoge@fuga.com')
            ->setTo('fuga@hoge.com')
            ->setSubject('job test')
            ->setBody('job mail test');

        $mailer = $this->getContainer()->get('mailer');
        $ret = $mailer->send($message);
        return true;
    }
}
```


### 4: Jobをキューに登録する

Jobのインスタンスから、キューに登録します。  


```
<?php
...

$job = new TestJob();
$this->getContainer()->get('sqs_job_queue.queue')->push($job);
```

### 5: ワーカーを実行する

ワーカーを実行する場合は`sqs_job_queue:worker-run`を実行します。

````
$ app/console sqs_job_queue:worker-run test
````

### 6: ワーカーを常駐させる場合

ワーカーを常駐する場合は`sqs_job_queue:worker-start`を実行します。

```
$ app/console sqs_job_queue:worker-start test
```


### 7: ワーカーを終了させる場合

常駐しているワーカーを停止する場合は`sqs_job_queue:worker-stop`を実行します。

```
$ app/console sqs_job_queue:worker-start test
```



## TODO
- ストレージがDoctrineしか使えない問題の解消
- 管理画面の提供
