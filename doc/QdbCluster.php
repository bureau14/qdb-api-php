<?php
namespace qdb;
/**
 * A connection to a *quasardb* cluster.
 *
 * @example
 * <code>
 * $cluster = new QdbCluster('qdb://127.0.0.1:2836');
 *
 * $cluster->blob('key 0')->put('value 0');
 * $cluster->deque('key 1')->pushBack('value 1');
 * $cluster->integer('key 2')->add(42);
 * </code>
 */
class QdbCluster
{
    /**
     * Connects to a *quasardb* cluster through the specified URI.
     *
     * The URI contains the addresses of the bootstraping nodes, other nodes are discovered during the first connection.
     * Having more than one node in the URI allows to connect to the cluster even if the first node is down.
     *
     * @param string $uri A quasardb URI, in the form of `qdb://<address1>:<port1>[,<address2:<port2>...]`.
     * @throws QdbConnectionRefusedException
     * @throws QdbTimeoutException
     *
     * @example
     * <code>
     * $cluster = new QdbCluster('qdb://127.0.0.1:2836');
     * </code>
     */
    function __construct($uri);

    /**
     * Creates a {@link QdbBlob} associated with the specified alias.
     *
     * No query is performed at this point.
     *
     * @example
     * <code>
     * $cluster->blob('alias')->put('content');
     * </code>
     *
     * @param string $alias The alias of the blob (alias starting with `qdb` are reserved).
     * @return QdbBlob
     */
    function blob($alias);

    /**
     * Creates a {@link QdbDeque} associated with the specified alias.
     *
     * No query is performed at this point.
     *
     * @example
     * <code>
     * $cluster->deque('alias')->pushBack('content');
     * </code>
     *
     * @param string $alias The alias of the queue (alias starting with `qdb` are reserved).
     * @return QdbDeque
     */
    function deque($alias);

    /**
     * Create a {@link QdbBlob}, a {@link QdbDeque}, a {@link QdbInteger} or a {@link QdbTag}
     * depending on the actual type of the entry.
     *
     * The entry must exist in the database.
     *
     * @example
     * <code>
     * $cluster->blob('alias1')->put('content');
     * $cluster->deque('alias2')->pushBack('content');
     *
     * @param string $alias The alias of the entry (alias starting with `qdb` are reserved).
     * @return QdbEntry
     * @throws QdbAliasNotFoundException
     *
     * $entry1 = $cluster->entry('alias1'); // $entry1 is a QdbBlob
     * $entry2 = $cluster->entry('alias2'); // $entry2 is a QdbDeque
     * </code>
     */
    function entry($alias);

    /**
     * Creates a {@link QdbInteger} associated with the specified alias.
     *
     * No query is performed at this point.
     *
     * @example
     * <code>
     * $cluster->integer('alias')->update(42);
     * </code>
     *
     * @param string $alias The alias of the integer (alias starting with `qdb` are reserved).
     * @return QdbInteger
     */
    function integer($alias);

    /**
     * Removes all the entries on all the nodes of the quasardb cluster.
     *
     * This operation is not allowed by default, it must be enabled in the server configuration.
     *
     * @example
     * <code>
     * $cluster->purgeAll();
     * </code>
     *
     * @param int $timeout The maximim number of seconds for the command to execute.
     * @throws QdbOperationDisabledException
     */
    function purgeAll($timeout=300);

    /**
     * Executes operations of a `QdbBatch`.
     *
     * @example
     * <code>
     * $batch = new QdbBatch();
     * $batch->put('alias1', 'content1');
     * $batch->put('alias2', 'content2');
     * $cluster->runBatch($batch);
     * </code>
     *
     * @param QdbBatch $batch The batch to run.
     * @return QdbBatchResult
     */
    function runBatch($batch);

    /**
     * Creates a {@link QdbTag} associated with the specified alias.
     *
     * No query is performed at this point.
     *
     * @example
     * <code>
     * $taggedEntries = $cluster->tag('alias')->getEntries();
     * </code>
     *
     * @param string $alias The alias of the tag (alias starting with `qdb` are reserved).
     * @return QdbTag
     */
    function tag($alias);
}
