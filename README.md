# quasardb PHP API

## Introduction

Using *quasardb* cluster from a PHP program is extremely straightforward, just create a `QdbCluster` and perform the operations.

    $nodes = array(array('address' => '127.0.0.1', 'port' => 2836));

    $cluster = new QdbCluster($nodes);
    $cluster->put('key 0', 'value 0');
    $cluster->put('key 1', 'value 1');
    $value2 = $cluster->get('key 2');

Want a queue in your database?

    $queue = $cluster->getQueue('my queue');
    $queue->pushBack('value 0');
    $queue->pushBack('value 1');

## Documentation

* [Installation instructions](doc/Installation.md)
* [The `QdbBatch` class](doc/QdbBatch.md)
* [The `QdbCluster` class](doc/QdbCluster.md)
* [The `QdbQueue` class](doc/QdbQueue.md)
