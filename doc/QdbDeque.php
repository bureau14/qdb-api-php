<?php
namespace qdb;
/**
 * A queue of blob in the database.
 *
 * It's a double-ended queue, you can both enqueue and dequeue from the front and the back.
 *
 * @example
 * You get a `QdbDeque` instance by calling {@link \QdbCluster::deque()}.
 * Then you can perform atomic operations on the queue:
 * <code>
 * $queue = $cluster->deque('my queue');
 * $queue->pushBack('value 0');
 * $queue->pushBack('value 1');
 * </code>
 */
class QdbDeque extends QdbEntry
{
    /**
     * Gets the element at the end of the queue.
     *
     * @example
     * <code>
     * $last = $cluster->deque('alias')->back();
     * </code>
     *
     * @return string The last element of the queue.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     * @throws QdbContainerEmptyException
     */
    function back();

    /**
     * Gets the element at the beginning of the queue.
     *
     * @example
     * <code>
     * $first = $cluster->deque('alias')->front();
     * </code>
     *
     * @return string The first element of the queue.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     * @throws QdbContainerEmptyException
     */
    function front();

    /**
     * Dequeues from the end of the queue and returns the value.
     *
     * @example
     * <code>
     * $last = $cluster->deque('alias')->popBack();
     * </code>
     *
     * @return string The last element of the queue.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     * @throws QdbContainerEmptyException
     */
    function popBack();

    /**
     * Dequeues from the beginning of the queue and returns the value.
     *
     * @example
     * <code>
     * $first = $cluster->deque('alias')->popFront();
     * </code>
     *
     * @return string The first element of the queue.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     * @throws QdbContainerEmptyException
     */
    function popFront();

    /**
     * Enqueues the specified value at the end of the queue. Creates the queue if needed.
     *
     * @example
     * <code>
     * $cluster->deque('alias')->pushBack('content');
     * </code>
     *
     * @param string $content The value to enqueue.
     * @throws QdbIncompatibleTypeException
     */
    function pushBack($content);

    /**
     * Enqueues the specified value at the beginning of the queue. Creates the queue if needed.
     *
     * @example
     * <code>
     * $cluster->deque('alias')->pushFront('content');
     * </code>
     *
     * @param string $content The value to enqueue.
     * @throws QdbIncompatibleTypeException
     */
    function pushFront($content);

    /**
     * Gets the length of the queue.
     *
     * @example
     * <code>
     * $elementCount = $cluster->deque('alias')->size();
     * </code>
     *
     * @return int The number of elements in the queue.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     */
    function size();
}

?>
