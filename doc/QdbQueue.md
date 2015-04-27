The `QdbQueue` class
====================

Represents a queue of blob in the *quasardb* database.
It's a double-ended queue, you can both enqueue and dequeue from the front and the back.

Example
-------

You get a `QdbQueue` instance by calling `QdbCluster::queue()`.
Then you can perform atomic operation on the queue:

    $queue = $cluster->queue('my queue');
    $queue->pushBack('value 0');
    $queue->pushBack('value 1');

Class synopsis
--------------

    QdbQueue
    {
        string alias ( )
        string popBack ( )
        string popFront ( )
        string pushBack ( string $content )
        string pushFront ( )
    }


QdbQueue's methods
------------------


### `string QdbQueue::alias ( )`

###### Description
Gets the alias (i.e. its "key") of the queue in the database.

###### Parameters
None.

###### Returns
The alias.


### `string QdbQueue::popBack ( )`

###### Description
Dequeues from the end of the queue and return the value.

###### Parameters
None.

###### Returns
The original value from the back of the queue.

###### Exceptions
- `QdbAliasNotFoundException` if the queue doesn't exist.
- `QdbEmptyContainerException` if the queue is empty.
- `QdbIncompatibleTypeException` if the entry is not a queue.


### `string QdbQueue::popFront ( )`

###### Description
Dequeues from the beginning of the queue and return the value.

###### Parameters
None.

###### Returns
The original value from the front of the queue.

###### Exceptions
- `QdbAliasNotFoundException` if the queue doesn't exist.
- `QdbEmptyContainerException` if the queue is empty.
- `QdbIncompatibleTypeException` if the entry is not a queue.


### `void QdbQueue::pushBack ( string $content )`

###### Description
Enqueues the specified value at the end of the queue.

###### Parameters
- `$content`: the value to enqueue.

###### Returns
Nothing

###### Exceptions
- `QdbIncompatibleTypeException` if the entry is not a queue.


### `void QdbQueue::pushFront ( string $content )`

###### Description
Enqueues the specified value at the beginning of the queue.

###### Parameters
- `$content`: the value to enqueue.

###### Returns
Nothing

###### Exceptions
- `QdbIncompatibleTypeException` if the entry is not a queue.



