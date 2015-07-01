# quasardb PHP API

## Introduction

Interfacing with a *quasardb* database from a PHP program is extremely straightforward, just create a [`QdbCluster`](doc/QdbCluster.md) and perform the operations.

    $cluster = new QdbCluster('qdb://127.0.0.1:2836');

OK, now that we have a connection to the database, let's store some binary data::

    $blob = $cluster->blob('Bob the blob');

    $blob->put('firstValue');
    $blob->update('secondValue');
    $value = $blob->get();

Want a queue? We have distributed queues.

    $queue = $cluster->queue('Andrew the queue');

    $queue->pushBack('firstValue');
    $queue->pushBack('secondValue');
    $value = $queue->popFront();

quasardb comes out of the box with server-side atomic integers:

    $integer = $cluster->integer('Roger the integer');
    $integer->put(42);
    $total = $integer->add(12);

We also provide distributed sets.

    $set = $cluster->hashSet('Janet the set');
    $set->insert('value');
    $hasValue = $set->contains('value');

Here's how you can easily find your data, using tags:

    $cluster->blob('Bob the blob')->addTag('Male');
    $cluster->integer('Roger the integer')->addTag('Male');

    $males = $cluster->tag('Male')->getEntries();

## Documentation

* [Installation instructions](doc/Installing.md)
* [Compilation instructions](doc/Compiling.md)
* [Configuration (php.ini)](doc/Configuration.md)
* [The `QdbBatch` class](doc/QdbBatch.md)
* [The `QdbBlob` class](doc/QdbBlob.md)
* [The `QdbCluster` class](doc/QdbCluster.md)
* [The `QdbHashSet` class](doc/QdbHashSet.md)
* [The `QdbInteger` class](doc/QdbInteger.md)
* [The `QdbQueue` class](doc/QdbQueue.md)
