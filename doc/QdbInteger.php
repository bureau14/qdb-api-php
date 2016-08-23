<?php
/**
 * A signed 64-bit integer in the database.
 *
 * @example
 * You get a `QdbInteger` instance by calling {@link \QdbCluster::integer()}.
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
     * @example
     * <code>
     * $total = $cluster->integer('alias')->add(42);
     * </code>
     */
    function add($value);

    /**
     * Reads the integer's value.
     * @return int The value of the integer.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     * @example
     * <code>
     * $value = $cluster->integer('alias')->get();
     * </code>
     */
    function get();

    /**
     * Creates the integer, fails if it already exists.
     * @param int $value The initial value of the integer.
     * @param int $expiry_time The absolute expiration time, in seconds since epoch (`0` means "never expires").
     * @throws QdbAliasAlreadyExistsException
     * @throws QdbIncompatibleTypeException
     * @example
     * <code>
     * $cluster->integer('alias')->put(42);
     * </code>
     */
    function put($value, $expiry_time=0);

    /**
     * Sets the integer's value. Creates the integer if needed.
     * @param int $value The new value of the integer.
     * @param int $expiry_time The absolute expiration time, in seconds since epoch (`0` means "never expires").
     * @return bool `true` if the integer was created; `false` if it was updated.
     * @throws QdbIncompatibleTypeException
     * @example
     * <code>
     * $cluster->integer('alias')->update(42);
     * </code>
     */
    function update($value, $expiry_time=0);
}
?>