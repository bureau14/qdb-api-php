<?php
/**
 * Thrown when an entry with the same alias is already present in the database.
 * @example
 * <code>
 * $cluster->blob('alias')->put('content1');
 * $cluster->blob('alias')->put('content2'); // throws QdbAliasAlreadyExistsException
 * </code>
 */
final class QdbAliasAlreadyExistsException extends QdbOperationException
{
}
?>