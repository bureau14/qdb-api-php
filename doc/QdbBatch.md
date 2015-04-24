The `QdbBatch` class
====================

Represents a collection of operation that can be executed with a single query.

Operation are executed by a call to `QdbCluster::runBatch ( $batch )`

Example
-------

    $batch = new QdbBatch();
    $batch->put('key 0', 'value 0');
    $batch->put('key 1', 'value 1');
    $batch->get('key 2');

    $result = $cluster->runBatch($batch);

    $value2 = $result[2];


Class synopsis
--------------

    QdbBatch
    {
        __construct ( void )
        void compareAndSwap ( string $alias , string $new_content , string $comparand [, int $expiry_time = 0 ] )
        void get ( string $alias )
        void getAndRemove ( string $alias )
        void getAndUpdate ( string $alias , string $content [, int $expiry_time = 0 ] )
        void put ( string $alias , string $content [, int $expiry_time = 0 ] )
        void remove ( string $alias )
        void removeIf ( string $alias , string $comparand )
        void update ( string $alias , string $content [, int $expiry_time = 0 ] )
    }


`QdbBatch`'s methods
--------------------


### `QdbBatch::__construct ( void )`

###### Description
Creates an empty batch, i.e. an empty collection of operation.

Batch operations can greatly increase performance when it is necessary to run many small operations.
Operations in a `QdbBatch` are not executed until `QdbCluster::runBatch ( $batch )` is called.


### `void QdbBatch::compareAndSwap ( string $alias , string $new_content , string $comparand [, int $expiry_time = 0 ] )`

###### Description
Adds a "compare and swap" operation to the batch.
When executed, the "compare and swap" operation atomically compares the entry with `$comparand` and updates it to `$new_content` if, and only if, they match.

###### Parameters
- `$alias`: a string representing the entry’s alias to compare to.
- `$new_content`: a string representing the entry’s content to be updated in case of match.
- `$comparand`: a string representing the entry’s content to be compared to.

###### Returns
The original value of the entry is stored in the array returned by `QdbCluster::runBatch ( $batch )`.

###### Exceptions
If the entry does not exist, a `QdbAliasNotFoundException` will be thrown when reading the value.


### `void QdbBatch::get ( string $alias )`

###### Description
Adds a "get" operation to the batch.
When executed, the "get" operation retrieves an entry's content.

###### Parameters
- `$alias`: a string representing the entry’s alias whose content is to be retrieved.

###### Returns
The value of the entry is stored in the array returned by `QdbCluster::runBatch ( $batch )`.

###### Exceptions
If the entry does not exist, a `QdbAliasNotFoundException` will be thrown when reading the value.


### `void QdbBatch::getAndRemove ( string $alias )`

###### Description
Adds a "get and remove" operation to the batch.
When executed, the "get and remove" operation atomically gets an entry and removes it.

###### Parameters
- `$alias`: a string representing the entry’s alias to delete.

###### Returns
The content of the entry is stored in the array returned by `QdbCluster::runBatch ( $batch )`.

###### Exceptions
If the entry does not exist, a `QdbAliasNotFoundException` will be thrown when reading the content.


### `void QdbBatch::getAndUpdate ( string $alias , string $content [, int $expiry_time = 0 ] )`

###### Description
Adds a "get and update" operation to the batch.
When executed, the "get and update" operation atomically gets and updates (in this order) the entry.

###### Parameters
- `$alias`: a string representing the entry’s alias to update.
- `$content`: a string representing the entry’s content to be set.
- `$expiry_time`: the absolute expiry time of the entry, in seconds, relative to epoch.

###### Returns
The content of the entry (before the update) is stored in the array returned by `QdbCluster::runBatch ( $batch )`.

###### Exceptions
If the entry does not exist, a `QdbAliasNotFoundException` will be throw when reading the value.


### `void QdbBatch::put ( string $alias , string $content [, int $expiry_time = 0 ] )`

###### Description
Adds a "put" operation to the batch.
When executed, the "put" operation adds an entry.

Alias beginning with "qdb" are reserved and cannot be used.

###### Parameters
- `$alias`: a string string representing the entry’s alias to create.
- `$content`: a string representing the entry’s content to be added.
- `$expiry_time`: the absolute expiry time of the entry, in seconds, relative to epoch

###### Exceptions
If the entry already exists, the operation will fail and a `QdbAliasAlreadyExistsException` will be thrown when reading the matching item of the array returned by `QdbCluster::runBatch ( $batch )`.


### `void QdbBatch::remove ( string $alias )`

###### Description
Adds a "remove" operation to the batch.
When executed, the "remove" operation removes an entry.

###### Parameters
- `$alias`: a string representing the entry’s alias to delete.

###### Exceptions
If the entry does not exist, the operation will fail and a `QdbAliasNotFoundException` will be thrown when reading the matching item of the array returned by `QdbCluster::runBatch ( $batch )`.


### `void QdbBatch::removeIf ( string $alias , string $comparand )`

###### Description
Adds a "remove if" operation to the batch.
When executed, the "remove if" operation removes an entry if it matches `$comparand`. The operation is atomic.

###### Parameters
- `$alias`: a string representing the entry’s alias to delete.
- `$comparand`: a string representing the entry’s content to be compared to.

###### Returns
The result of the operation, `true` if the entry was actually removed or `false` otherwise, is stored in the array returned by `QdbCluster::runBatch ( $batch )`.

###### Exceptions
If the entry does not exist, the operation will fail and a `QdbAliasAlreadyExistsException` will be throw when reading the matching item of the array returned by `QdbCluster::runBatch ( $batch )`.


### `void QdbBatch::update ( string $alias , string $content [, int $expiry_time = 0 ] )`

###### Description
Adds an "update" operation to the batch.
When executed, the "update" operation updates an entry. If the entry already exists, the content will be updated. If the entry does not exist, it will be created.

Alias beginning with "qdb" are reserved and cannot be used.

###### Parameters
- `$alias`: a string representing the entry’s alias to update.
- `$content`: a string representing the entry’s content to be added.
- `$expiry_time`: the absolute expiry time of the entry, in seconds, relative to epoch

