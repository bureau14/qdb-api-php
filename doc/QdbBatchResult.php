<?php
/**
 * A result of a batch query.
 *
 * A `QdbBatchResult` is returned by {@link \QdbCluster::runBatch()}.
 *
 * This class behaves like an array containing the operation results.
 * Operations results are stored in the order in which operations have been added to the {@link QdbBatch}, which is not necessarily the order in which operation are executed in the cluster.
 *
 * An exception will be thrown when reading the result of an operation that failed.
 * The exception type will match the failure of that particular operation, like {@link QdbAliasAlreadyExistsException}, {@link QdbAliasNotFoundException}, {@link QdbContainerEmptyException} or {@link QdbIncompatibleTypeException}.
 *
 * @example
 * <code>
 * // first, prepare the batch
 * $batch = new QdbBatch();
 * $batch->put('key 0', 'value 0');
 * $batch->put('key 1', 'value 1');
 * $batch->get('key 2');
 *
 * // then, execute it
 * $result = $cluster->runBatch($batch);
 *
 * // finally, read the results
 * // (the following line may throw QdbAliasNotFoundException or QdbIncompatibleTypeException)
 * $value2 = $result[2];
 * </code>
 */
class QdbBatchResult implements ArrayAccess, Countable
{
}
?>
