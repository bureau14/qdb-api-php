<?php

/**
 * Abstract entry in the database
 */
abstract class QdbEntry
{
    /**
     * Gets the alias (i.e. its "key") of the entry.
     * Alias starting with `qdb` are reserved.
     * @return string The alias of the entry.
     */
    function alias();

    /**
     * Deletes the entry.
     * @throws QdbAliasNotFoundException
     */
    function remove();
}
?>