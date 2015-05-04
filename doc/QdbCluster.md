The `QdbCluster` class
======================

Represents a connection to a *quasardb* cluster.

Example
-------

    $cluster = new QdbCluster('qdb://127.0.0.1:2836');

    $cluster->blob('key 0')->put('value 0');
    $cluster->queue('key 1')->push_back('value 1');
    $cluster->integer('key 2')->add(42);
    $cluster->hashSet('key 3')->insert('value 2');

Class synopsis
--------------

    QdbCluster
    {
        __construct ( string $uri )
        QdbBlob blob ( string $alias )
        QdbHashSet hashSet ( string $alias )
        QdbInteger integer ( string $alias )
        QdbQueue queue ( string $alias )
        array runBatch ( QdbBatch $batch )
    }


`QdbCluster`'s methods
--------------------


### `QdbCluster::__construct ( string $uri )`

###### Description
Connects to a *quasardb* cluster through the specified URI.
The URI contains the addresses of the bootstraping nodes, other nodes are discovered during the first connection.
Having more than one node in the URI allows to connect to the cluster even if the first node is down.

###### Parameters
- `$uri`: a string in the form of `qdb://<address1>:<port1>[,<address2:<port2>...]`.

###### Exceptions
- `QdbConnectionRefusedException` if the connection was rejected by the node.
- `QdbTimeoutException` if the connection times out.


### `QdbBlob QdbCluster::blob ( string $alias )`

###### Description
Creates a [`QdbBlob`](QdbBlob.md) associated with the specified alias.
No query is performed at this point.

###### Parameters
- `$alias`: the alias of the blob in the database.

###### Returns
The [`QdbBlob`](QdbBlob.md).

###### Exceptions
None.


### `QdbHashSet QdbCluster::hashSet ( string $alias )`

###### Description
Creates a [`QdbHashSet`](QdbHashSet.md) associated with the specified alias.
No query is performed at this point.

###### Parameters
- `$alias`: the alias of the queue in the database.

###### Returns
The [`QdbHashSet`](QdbHashSet.md).

###### Exceptions
None.


### `QdbInteger QdbCluster::integer ( string $alias )`

###### Description
Creates a [`QdbInteger`](QdbInteger.md) associated with the specified alias.
No query is performed at this point.

###### Parameters
- `$alias`: the alias of the integer in the database.

###### Returns
The [`QdbInteger`](QdbInteger.md).

###### Exceptions
None.


### `QdbQueue QdbCluster::queue ( string $alias )`

###### Description
Creates a [`QdbQueue`](QdbQueue.md) associated with the specified alias.
No query is performed at this point.

###### Parameters
- `$alias`: the alias of the queue in the database.

###### Returns
The [`QdbQueue`](QdbQueue.md).

###### Exceptions
None.


### `array QdbCluster::runBatch ( QdbBatch $batch )`

###### Description
Executes operations of a [`QdbBatch`](QdbBatch.md).

###### Parameters
- `$batch`: a [`QdbBatch`](QdbBatch.md) containing the operations to be performed.

###### Returns
Returns an array (more exactly a class `QdbBatchResult` that behaves like an array) with the operation results.
Operations results are stored in the order in which operations have been added to the [`QdbBatch`](QdbBatch.md), which is not necessarily the order in which operation are executed in the cluster.

###### Exceptions
An exception related to an operation will be thrown when reading the matching item from the returned array.
