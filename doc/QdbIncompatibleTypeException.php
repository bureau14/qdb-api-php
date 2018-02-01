<?php
namespace qdb;
/**
 * Thrown when an operation cannot be perform because the existing entry do not have the appropriate type.
 *
 * @example
 * <code>
 * $cluster->blob('alias')->put('content1');
 * $cluster->integer('alias')->get(); // throws QdbIncompatibleTypeException
 * </code>
 */
final class QdbIncompatibleTypeException extends QdbOperationException
{
}
?>
