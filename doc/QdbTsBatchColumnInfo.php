
<?php
namespace qdb;
/**
 * A simple class storing a column's name and it's timeseries's name.
 */
class QdbTsBatchColumnInfo
{
    /**
     * Create the column info.
     * 
     * @param string $timeseries
     * @param string $column
     */
    function __construct($timeseries, $column);

    /**
     * Return the name of the column's timeseries.
     * 
     * @return string
     */
    function timeseries();
    
    /**
     * Return the column's name.
     * 
     * @return string
     */
    function column();
}
?>
