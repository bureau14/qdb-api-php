<?php
namespace qdb;
/**
 * An entry which can expire.
 */
abstract class QdbExpirableEntry extends QdbEntry
{
    /**
     * Sets the absolute expiration time of the entry.
     * @param int $expiry_time The absolute expiration time, in seconds since epoch (`0` means "never expires").
     * @throws QdbAliasNotFoundException
     * @example
     * <code>
     * // Expire entry in two days
     * $cluster->blob('Milk bottle')->expiresAt(time()+172800);
     * </code>
     */
    function expiresAt($expiry_time);

    /**
     * Sets the relative expiration time of the entry.
     * @param int $time_delta The relative expiration time, in seconds.
     * @throws QdbAliasNotFoundException
     * @example
     * <code>
     * // Expire entry in two days
     * $cluster->blob('Milk bottle')->expiresFromNow(172800);
     * </code>
     */
    function expiresFromNow($time_delta);

    /**
     * Gets the expiration time of the entry.
     * @return int The absolute expiration time, in seconds since epoch (`0` means "never expires").
     * @throws QdbAliasNotFoundException
     * @example
     * <code>
     * $expiryTime = $cluster->blob('Milk bottle')->getExpiryTime();
     * </code>
     */
    function getExpiryTime();
}
?>
