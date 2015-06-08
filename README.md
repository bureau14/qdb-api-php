# quasardb PHP API

## Introduction

Interfacing with a *quasardb* database from a PHP program is extremely straightforward, just create a [`QdbCluster`](doc/QdbCluster.md) and perform the operations.

    $cluster = new QdbCluster('qdb://127.0.0.1:2836');

OK, now that we have a connection to the cluster, let's store some [binary data](doc/QdbBlob.md):

    $blob = $cluster->blob('myEntry');
    $blob->put('firstValue');
    $blob->update('secondValue');
    $value = $blob->get();

Want a [queue](doc/QdbQueue.md) in your database?

    $queue = $cluster->queue('my queue');
    $queue->pushBack('firstValue');
    $queue->pushBack('secondValue');

Want [atomic integers](doc/QdbInteger.md) now?

    $integer = $cluster->integer('my atomic int');
    $integer->put(42);
    $total = $integer->add(12);

What else? a [set](doc/QdbHashSet.md) maybe?

    $set = $cluster->hashSet('my set');
    $set->insert('value');
    $hasValue = $set->contains('value');

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
