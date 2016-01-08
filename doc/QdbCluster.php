<?php
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
 * $cluster->hashSet('key 3')->insert('value 2');
 * </code>
 */
class QdbCluster
{
    /**
     * Connects to a *quasardb* cluster through the specified URI.
     *
     * The URI contains the addresses of the bootstraping nodes, other nodes are discovered during the first connection.
     * Having more than one node in the URI allows to connect to the cluster even if the first node is down.
     * @param string $uri A quasardb URI, in the form of `qdb://<address1>:<port1>[,<address2:<port2>...]`.
     * @throws QdbConnectionRefusedException
     * @throws QdbTimeoutException
     * @example
     * <code>
     * $cluster = new QdbCluster('qdb://127.0.0.1:2836');
     * </code>
     */
    function __construct($uri);

    /**
     * Creates a {@link QdbBlob} associated with the specified alias.
     * No query is performed at this point.
     * @param string $alias The alias of the blob (alias starting with `qdb` are reserved).
     * @return QdbBlob
     * @example
     * <code>
     * $cluster->blob('alias')->put('content');
     * </code>
     */
    function blob($alias);

    /**
     * Creates a {@link QdbDeque} associated with the specified alias.
     * No query is performed at this point.
     * @param string $alias The alias of the queue (alias starting with `qdb` are reserved).
     * @return QdbDeque
     * @example
     * <code>
     * $cluster->deque('alias')->pushBack('content');
     * </code>
     */
    function deque($alias);

    /**
     * Create a {@link QdbBlob}, a {@link QdbDeque}, a {@link QdbHashSet}, a {@link QdbInteger} or a {@link QdbTag}
     * depending on the actual type of the entry.
     * The entry must exist in the database.
     * @param string $alias The alias of the entry (alias starting with `qdb` are reserved).
     * @return QdbEntry
     * @throws QdbAliasNotFoundException
     * @example
     * <code>
     * $cluster->blob('alias1')->put('content');
     * $cluster->deque('alias2')->pushBack('content');
     *
     * $entry1 = $cluster->entry('alias1'); // $entry1 is a QdbBlob
     * $entry2 = $cluster->entry('alias2'); // $entry2 is a QdbDeque
     * </code>
     */
    function entry($alias);

    /**
     * Creates a {@link QdbHashSet} associated with the specified alias.
     * No query is performed at this point.
     * @param string $alias The alias of the hash-set (alias starting with `qdb` are reserved).
     * @return QdbHashSet
     * @example
     * <code>
     * $cluster->hashSet('alias')->insert('content');
     * </code>
     */
    function hashSet($alias);

    /**
     * Creates a {@link QdbInteger} associated with the specified alias.
     * No query is performed at this point.
     * @param string $alias The alias of the integer (alias starting with `qdb` are reserved).
     * @return QdbInteger
     * @example
     * <code>
     * $cluster->integer('alias')->update(42);
     * </code>
     */
    function integer($alias);

    /**
     * Removes all the entries on all the nodes of the quasardb cluster.
     * This operation is not allowed by default, it must be enabled in the server configuration.
     * @throws QdbOperationDisabledException
     * @example
     * <code>
     * $cluster->purgeAll();
     * </code>
     */
    function purgeAll();

    /**
     * Executes operations of a `QdbBatch`.
     * @param QdbBatch $batch The batch to run.
     * @return QdbBatchResult
     * @example
     * <code>
     * $batch = new QdbBatch();
     * $batch->put('alias1', 'content1');
     * $batch->put('alias2', 'content2');
     * $cluster->runBatch($batch);
     * </code>
     */
    function runBatch($batch);

    /**
     * Creates a {@link QdbTag} associated with the specified alias.
     * No query is performed at this point.
     * @param string $alias The alias of the tag (alias starting with `qdb` are reserved).
     * @return QdbTag
     * @example
     * <code>
     * $taggedEntries = $cluster->tag('alias')->getEntries();
     * </code>
     */
    function tag($alias);
}
