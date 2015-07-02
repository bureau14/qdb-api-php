<?php

/**
 * Represents a connection to a *quasardb* cluster.
 *
 * @Example
 * <code>
 * $cluster = new QdbCluster('qdb://127.0.0.1:2836');
 *
 * $cluster->blob('key 0')->put('value 0');
 * $cluster->queue('key 1')->push_back('value 1');
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
     */
    function __construct($uri);

    /**
     * Creates a `QdbBlob` associated with the specified alias.
     * No query is performed at this point.
     * @param string $alias The alias of the blob (alias starting with `qdb` are reserved).
     * @return QdbBlob
     */
    function blob($alias);

    /**
     * Creates a `QdbHashSet` associated with the specified alias.
     * No query is performed at this point.
     * @param string $alias The alias of the hash-set (alias starting with `qdb` are reserved).
     * @return QdbHashSet
     */
    function hashSet($alias);

    /**
     * Creates a `QdbInteger` associated with the specified alias.
     * No query is performed at this point.
     * @param string $alias The alias of the hash-set (alias starting with `qdb` are reserved).
     * @return QdbInteger
     */
    function integer($alias);

    /**
     * Creates a `QdbQueue` associated with the specified alias.
     * No query is performed at this point.
     * @param string $alias The alias of the hash-set (alias starting with `qdb` are reserved).
     * @return QdbQueue
     */
    function queue($alias);

    /**
     * Executes operations of a `QdbBatch`.
     * @param QdbBatch $batch The batch to run.
     * @return QdbBatchResult
     */
    function runBatch($batch);
}
