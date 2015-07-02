<?php

/**
 * An entry which can expire.
 */
abstract class QdbExpirableEntry extends QdbEntry
{
    /**
     * Sets the absolute expiration time of the entry.
     * @param int $expiry_time The absolute expiration time, in seconds since epoch (0 means "never expires").
     * @throws QdbAliasNotFoundException
     */
    function expiresAt($expiry_time);

    /**
     * Sets the relative expiration time of the entry.
     * @param int $time_delta The relative expiration time, in seconds.
     * @throws QdbAliasNotFoundException
     */
    function expiresFromNow($time_delta);
}
?>