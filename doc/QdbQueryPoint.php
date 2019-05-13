
<?php
namespace qdb;
/**
 * A variadic value which can be a {@link QdbTimestamp}, an int64, a double, a string or a count.
 * Accessed from a {@link QdbQueryTable}.
 */
class QdbQueryPoint
{
    /**
     * The possible types of a point.
     */
    const BLOB;
    const COUNT;
    const DOUBLE;
    const INT64;
    const TIMESTAMP;

    /**
     * Get one of the constants above.
     * 
     * @return int
     */
    function type();

    /**
     * Get the point's value.
     * 
     * @return string|int|double|QdbTimestamp
     */
    function value();
}
?>
