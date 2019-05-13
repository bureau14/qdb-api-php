
<?php
namespace qdb;
/**
 * A simple class representing a timestamp.
 */
class QdbTimestamp
{
    /**
     * Create the timestamp.
     * 
     * @param int $seconds
     * @param int $nanoseconds
     */
    function __construct($seconds, $nanoseconds);

    /**
     * Return the timestamp's number of seconds since 01-01-1970:00.00.
     * 
     * @return int
     */
    function seconds();
    
    /**
     * Return the timestamp's number of nanoseconds.
     * 
     * @return int
     */
    function seconds();
}
?>
