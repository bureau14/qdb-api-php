The `QdbCluster` class
======================

Represents a connection to a *quasardb* cluster.

Example
-------

    $nodes = array(array('address' => '127.0.0.1', 'port' => 2836));
    $cluster = new QdbCluster($nodes);

    $cluster->blob('key 0')->put('value 0');
    $cluster->queue('key 1').push_back('value 1');
    $cluster->integer('key 2').add(42);

Class synopsis
--------------

    QdbCluster
    {
        __construct ( array $nodes )
        QdbBlob blob ( string $alias )
        QdbInteger integer ( string $alias )
        QdbQueue queue ( string $alias )
        array runBatch ( QdbBatch $batch )
    }


`QdbCluster`'s methods
--------------------


### `QdbCluster::__construct ( array $nodes )`

###### Description
Connects to a *quasardb* cluster through the specified nodes.

###### Parameters
`$nodes` is an array of array:

    $nodes = array(
        array('address'=>'192.168.0.1','port'=>'2836'),
        array('address'=>'192.168.0.2','port'=>'2836')
    );

###### Exceptions
Throws a `QdbClusterConnectionFailedException` if the connection **to every node** fails.


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
