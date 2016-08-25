<?php
/**
 * A tag in the database.
 *
 * A tag is a lightweight means of finding entries in the database.
 *
 * @example
 * You get a `QdbTag` instance by calling {@link \QdbCluster::tag()}.
 * Then enumerate entry having this tag:
 * <code>
 * $users = $cluster->tag('user')->getEntries();
 * foreach ($user as $user) {
 *     echo($user->alias());
 * }
 * </code>
 */
class QdbTag extends QdbEntry
{
    /**
     * Attaches the tag to an entry.
     *
     * @param QdbEntry|string $entry The entry to add the tag to, or its alias.
     * @return bool `true` if the tag was added, or `false` it the entry already had this tag.
     * @throws QdbIncompatibleTypeException
     *
     * @example
     * <code>
     * $cluster->tag('male')->attachEntry('Bob the Blob');
     * // same as $cluster->blob('Bob the Blob')->attachTag('male');
     * </code>
     */
    function attachEntry($entry);

    /**
     * Enumerates tagged entries.
     *
     * @example
     * <code>
     * $males = $cluster->tag('male')->getEntries();
     * foreach ($males as $entry) {
     *     echo($entry->alias() . ' is a male');
     * }
     * </code>
     *
     * @return QdbEntryCollection A traversable collection of {@link QdbEntry}.
     * @throws QdbIncompatibleTypeException
     */
    function getEntries();

    /**
     * Checks if an entry is attached with the tag.
     *
     * @example
     * <code>
     * $bob_is_male = $cluster->tag('male')->hasEntry('Bob the Blob');
     * // same as $bob_is_male = $cluster->blob('Bob the Blob')->hasTag('male');
     * </code>
     *
     * @param QdbEntry|string $entry The entry to check, or its alias.
     * @return bool `true` if the entry is tagged with the given tag, `false` otherwise.
     */
    function hasEntry($entry);

    /**
     * Detachs the tag from an entry.
     *
     * @example
     * <code>
     * $cluster->tag('stupid name')->detachEntry('Bob the Blob');
     * // same as $cluster->blob('Bob the Blob')->detachTag('stupid name');
     * </code>
     *
     * @param QdbEntry|string $entry The entry to remove the tag from, or its alias.
     * @return bool `true` if the tag was removed, or `false` it the entry already didn't have this tag.
     * @throws QdbIncompatibleTypeException
     */
    function detachEntry($entry);
}
?>
