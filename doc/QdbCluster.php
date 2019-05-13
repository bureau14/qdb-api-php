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
     * Create a batch table to insert data in timeseries.
     * 
     * @param QdbTsBatchColumnInfo[] $columnsInfo The columns and their timeseries names.
     * @return QdbTsBatchTable
     * @throws QdbException If one of the columns does not exists, or another error (connection, ...).
     */
    function makeBatchTable($columnsInfo);

    /**
     * Run the given query. Return it's result on success.
     * 
     * @param string $command The query string.
     * @return QdbQuery
     * @throws QdbException If the query fails, on or another error (connection, ...).
     */
    function makeQuery($command);

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
     * Create a {@link QdbBlob}, a {@link QdbInteger} or a {@link QdbTag}
     * depending on the actual type of the entry.
     *
     * The entry must exist in the database.
     *
     * @example
     * <code>
     * $cluster->blob('alias1')->put('content');
     *
     * @param string $alias The alias of the entry (alias starting with `qdb` are reserved).
     * @return QdbEntry
     * @throws QdbAliasNotFoundException
     *
     * $entry1 = $cluster->entry('alias1'); // $entry1 is a QdbBlob
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
