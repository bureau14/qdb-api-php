<?php
/**
 * Thrown when an entry cannot be found in the database.
 * @example
 * <code>
 * $cluster->blob('alias')->remove();
 * $cluster->blob('alias')->get(); // throws QdbAliasNotFoundException
 * </code>
 */
class QdbAliasNotFoundException extends QdbException
{
}
?>