
<?php
namespace qdb;
/**
 * A storage which is used to insert data into existing timeseries.
 *
 * @example
 * You create a query with {@link QdbCluster}.
 * <code>
 * $batch = $cluster->makeBatchTable([
 *     new QdbTsBatchColumnInfo('stocks', 'price'),
 *     new QdbTsBatchColumnInfo('stocks', 'volume')
 * ]);
 * $batch->startRow(new Timestamp(10, 0));
 * $batch->setDouble(0, 120.5);
 * $batch->setInt64 (1, 70);
 * $batch->pushValues();
 * </code>
 */
class QdbTsBatchTable
{
    /**
     * Start a row of values.
     * 
     * @param QdbTimestamp $timestamp The key of every values in this row.
     * @throws QdbException
     */
    function startRow($timestamp);

    /**
     * Set a blob in the current row at the given index.
     * 
     * @param int $index The blob's column index.
     * @param string $blob The blob's value.
     * @throws QdbException On type mismatch.
     */
    function setBlob($index, $blob);

    /**
     * Set a double in the current row at the given index.
     * 
     * @param int $index The double's column index.
     * @param double $num The value.
     * @throws QdbException On type mismatch.
     */
    function setDouble($index, $num);

    /**
     * Set a int in the current row at the given index.
     * 
     * @param int $index The int's column index.
     * @param int $num The value.
     * @throws QdbException On type mismatch.
     */
    function setInt64($index, $num);

    /**
     * Set a timestamp in the current row at the given index.
     * 
     * @param int $index The timestamp's column index.
     * @param QdbTimestamp $num The value.
     * @throws QdbException On type mismatch.
     */
    function setTimestamp($index, $tiemstamp);

    /**
     * Insert all the values from the batch table in the columns.
     * 
     * @throws QdbException
     */
    function pushValues();

    /**
     * Insert all the values from the batch table in the columns.
     * Does NOT wait for the insertion to finish.
     * 
     * @throws QdbException
     */
    function pushValuesAsync();
}
?>
