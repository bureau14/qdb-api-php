<?php
/**
 * Thrown when the container is empty.
 * @example
 * <code>
 * $cluster->deque('alias')->pushBack('content');
 * $cluster->deque('alias')->popBack();
 * $cluster->deque('alias')->popBack(); // throws QdbContainerEmptyException
 * </code>
 */
final class QdbContainerEmptyException extends QdbOperationException
{
}
?>
