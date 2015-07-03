<?php

/**
 * A queue of blob in the database.
 *
 * It's a double-ended queue, you can both enqueue and dequeue from the front and the back.
 *
 * @example
 * You get a `QdbQueue` instance by calling {@link \QdbCluster::queue()}.
 * Then you can perform atomic operations on the queue:
 * <code>
 * $queue = $cluster->queue('my queue');
 * $queue->pushBack('value 0');
 * $queue->pushBack('value 1');
 * </code>
 */
class QdbQueue extends QdbEntry
{
    /**
     * Gets the element at the end of the queue.
     * @return string The last element of the queue.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     * @throws QdbEmptyContainerException
     * @example
     * <code>
     * $last = $cluster->queue('alias')->back();
     * </code>
     */
    function back();

    /**
     * Gets the element at the beginning of the queue.
     * @return string The first element of the queue.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     * @throws QdbEmptyContainerException
     * @example
     * <code>
     * $first = $cluster->queue('alias')->front();
     * </code>
     */
    function front();

    /**
     * Dequeues from the end of the queue and return the value.
     * @return string The last element of the queue.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     * @throws QdbEmptyContainerException
     * @example
     * <code>
     * $last = $cluster->queue('alias')->popBack();
     * </code>
     */
    function popBack();

    /**
     * Dequeues from the beginning of the queue and return the value.
     * @return string The first element of the queue.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     * @throws QdbEmptyContainerException
     * @example
     * <code>
     * $first = $cluster->queue('alias')->popFront();
     * </code>
     */
    function popFront();

    /**
     * Enqueues the specified value at the end of the queue. Creates the queue if needed.
     * @param string $content The value to enqueue.
     * @throws QdbIncompatibleTypeException
     * @example
     * <code>
     * $cluster->queue('alias')->pushBack('content');
     * </code>
     */
    function pushBack($content);

    /**
     * Enqueues the specified value at the beginning of the queue. Creates the queue if needed.
     * @param string $content The value to enqueue.
     * @throws QdbIncompatibleTypeException
     * @example
     * <code>
     * $cluster->queue('alias')->pushFront('content');
     * </code>
     */
    function pushFront($content);
}

?>
