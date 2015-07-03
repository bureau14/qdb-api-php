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
     * Adds current tag to the specified entry.
     * @example
     * <code>
     * $cluster->tag('male')->addEntry('Bob the Blob');
     * // same as $cluster->blob('Bob the Blob')->addTag('male');
     * </code>
     * @param QdbEntry|string $entry The entry to add the tag to, or its alias.
     * @return bool `true` if the tag was added, or `false` it the entry already had this tag.
     * @throws QdbIncompatibleTypeException
     */
    function addEntry($entry);

    /**
     * Enumerate tagged entries.
     * @example
     * <code>
     * $males = $cluster->tag('male')->getEntries();
     * foreach ($males as $entry) {
     *     echo($entry->alias() . ' is a male');
     * }
     * </code>
     * @return QdbEntryCollection A traversable collection of {@link QdbEntry}.
     * @throws QdbIncompatibleTypeException
     */
    function getEntries();

    /**
     * Removes current tag to the specified entry.
     * @example
     * <code>
     * $cluster->tag('stupid name')->removeEntry('Bob the Blob');
     * // same as $cluster->blob('Bob the Blob')->removeTag('stupid name');
     * </code>
     * @param QdbEntry|string $entry The entry to remove the tag from, or its alias.
     * @return bool `true` if the tag was removed, or `false` it the entry already didn't have this tag.
     * @throws QdbIncompatibleTypeException
     */
    function removeEntry($entry);
}
?>