
<?php
namespace qdb;
/**
 * The result of a query for a single table.
 */
class QdbQueryTable
{
    /**
     * Get the table's name.
     * 
     * @return string
     */
    function tableName();

    /**
     * Get the columns's names.
     * The first column of a timeseries contains the timestamps.
     * 
     * @return string[]
     */
    function columnsNames();

    /**
     * Return of array of rows.
     * Each row is an array of points :
     * <code>
     * $point = $table->PointsRows()[row_index][column_index];
     * </code>
     * 
     * @return QdbQueryPoint[][]
     */
    function PointsRows();
}
?>
