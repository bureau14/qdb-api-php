<?php
/**
 * Thrown when the container is empty.
 * @example
 * <code>
 * $cluster->queue('alias')->pushBack('content');
 * $cluster->queue('alias')->popBack();
 * $cluster->queue('alias')->popBack(); // throws QdbEmptyContainerException
 * </code>
 */
class QdbEmptyContainerException extends QdbException
{
}
?>