
<?php
namespace qdb;
/**
 * The result of a query ran on a cluster, available on success.
 *
 * @example
 * You create a query with {@link QdbCluster}.
 * <code>
 * $query = $cluster->makeQuery("SELECT * FROM persons, stocks");
 * foreach ($query->tables() as $table) {
 *     foreach ($table->pointsRows() as $row) {
 *         foreach ($row as $point) {
 *             // Do something with the points
 *         }
 *     }
 * }
 * </code>
 */
class QdbQuery
{
    /**
     * Get an array of results for each table concerned by the query.
     * 
     * @return QdbQueryTable[]
     */
    function tables();

    /**
     * Get the total number of points obtained in the results.
     * 
     * @return int
     */
    function scannedPointCount();
}
?>
