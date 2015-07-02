<?php

/**
 * Represents an signed 64-bit integer in a *quasardb* database.
 *
 * @example
 * You get a `QdbInteger` instance by calling `QdbCluster::integer()`.
 * Then you can perform atomic operations on the integer:
 * <code>
 * $cluster->integer('key 0')->put(1);
 * $cluster->integer('key 1')->update(2);
 * $value2 = $cluster->integer('key 2')->get();
 * </code>
 */
class QdbInteger extends QdbExpirableEntry
{
    /**
     * Atomically increment the value in the database.
     * @param int $value The value to add to the integer.
     * @return int The new value (i.e. after the addition) of the integer.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     */
    function add($value);

    /**
     * Reads the integer's value.
     * @return int The value of the integer.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     */
    function get();

    /**
     * Creates the integer, fails if it already exists.
     * @param int $value The initial value of the integer.
     * @param int $expiry_time The absolute expiration time, in seconds since epoch (0 means "never expires").
     * @throws QdbAliasAlreadyExistsException
     * @throws QdbIncompatibleTypeException
     */
    function put($value, $expiry_time=0);

    /**
     * Sets the integer's value. Creates the integer if needed.
     * @param int $value The new value of the integer.
     * @param int $expiry_time The absolute expiration time, in seconds since epoch (0 means "never expires").
     * @throws QdbIncompatibleTypeException
     */
    function update($value, $expiry_time=0);
}
?>
