<?php
namespace qdb;
/**
 * An unordered set of blob in the database.
 *
 * @example
 * You get a `QdbHashSet` instance by calling {@link \QdbCluster::hashSet()}.
 * Then you can perform atomic operations on the set:
 * <code>
 * $hashSet = $cluster->hashSet('my hashSet');
 * $hashSet->insert('value');
 * $hasValue = $hashSet->contains('value');
 * </code>
 */
class QdbHashSet extends QdbEntry
{
    /**
     * Tells if a value is present in the set.
     *
     * @example
     * <code>
     * $needMilk = $cluster->hashSet('recipe')->contains('milk');
     * </code>
     *
     * @param string $value The value to look for.
     * @return bool `true` if the value is present in the set; `false` if not.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     */
    function contains($value);

    /**
     * Removes a value from the set.
     *
     * @example
     * <code>
     * $cluster->hashSet('recipe')->erase('butter');
     * </code>
     *
     * @param string $value The value to remove.
     * @return bool `true` if the value was present in the set; `false` if not.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     */
    function erase($value);

    /**
     * Adds the a value in the set. Creates the set if needed.
     *
     * @example
     * <code>
     * $cluster->hashSet('recipe')->insert('flour');
     * </code>
     *
     * @param string $value The value to add.
     * @return bool `true` if the value was added; `false` if it was already present in the set.
     * @throws QdbIncompatibleTypeException
     */
    function insert($value);
}
?>
