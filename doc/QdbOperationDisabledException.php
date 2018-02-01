<?php
namespace qdb;
/**
 * Thrown when an operation cannot be perform because it has been disabled in the cluster configuration.
 *
 * @example
 * <code>
 * $cluster->purgeAll(); // throws QdbOperationDisabledException
 * </code>
 */
final class QdbOperationDisabledException extends QdbOperationException
{
}
?>
