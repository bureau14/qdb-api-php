# quasardb PHP API

## Introduction

Using *quasardb* cluster from a PHP program is extremely straightforward, just create a [`QdbCluster`](doc/QdbCluster.md) and perform the operations.

    $nodes = array(array('address' => '127.0.0.1', 'port' => 2836));
    $cluster = new QdbCluster($nodes);

OK, now that we have a connection to the cluster, let's store some [binary data](doc/QdbBlob.md):

    $blob = $cluster->blob('myEntry');
    $blob->put('firstValue');
    $blob->update('secondValue');
    $value = $blob->get('myEntry');

Want a [queue](doc/QdbQueue.md) in your database?

    $queue = $cluster->queue('my queue');
    $queue->pushBack('firstValue');
    $queue->pushBack('secondValue');

Want [atomic integers](doc/QdbInteger.md) now?

    $integer = $cluster->integer('my atomic int');
    $integer->put(42);
    $total = $integer->add(12);

## Documentation

* [Installation instructions](doc/Installation.md)
* [The `QdbBatch` class](doc/QdbBatch.md)
* [The `QdbBlob` class](doc/QdbBlob.md)
* [The `QdbCluster` class](doc/QdbCluster.md)
* [The `QdbInteger` class](doc/QdbInteger.md)
* [The `QdbQueue` class](doc/QdbQueue.md)
