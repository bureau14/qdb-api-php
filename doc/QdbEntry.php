<?php

/**
 * An entry in the database
 */
abstract class QdbEntry
{
    /**
     * Adds a tag to the entry
     *
     * @example
     * <code>
     * $cluster->blob('Bob the blob')->addTag('male');
     * </code>
     *
     * @param QdbTag|string $tag The tag to add to the entry, or its alias.
     * @return bool `true` if the tag was added, or `false` it the entry already had this tag.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     */
    function addTag($tag);

    /**
     * Gets the alias (i.e. its "key") of the entry.
     * Alias starting with `qdb` are reserved.
     * @return string The alias of the entry.
     */
    function alias();

    /**
     * Gets entry's tags.
     *
     * @example
     * <code>
     * $bobsTags = $cluster->blob('Bob the blob')->getTags();
     * foreach ($bobsTags as $tag) {
     *     echo('Bob has the tag ' . $tag->alias());
     * }
     * </code>
     *
     * @param QdbTag|string $tag The tag to the entry, or it's alias.
     * @return QdbTagCollection A traversable collection of {@link QdbTag}
     * @throws QdbAliasNotFoundException
     */
    function getTags($tag);

    /**
     * Checks if the current entry is tagged with the specified tag.
     * @example
     * <code>
     * $bob_is_male = $cluster->blob('Bob the Blob')->hasTag('male');
     * // same as $bob_is_male = $cluster->tag('male')->hasEntry('Bob the Blob');
     * </code>
     * @param QdbTag|string $tag The tag to check for, or its alias.
     * @return bool `true` if the entry is tagged with the given tag, `false` otherwise.
     */
    function hasTag($tag);

    /**
     * Deletes the entry.
     *
     * @example
     * <code>
     * // Bye-bye Bob :-(
     * $cluster->blob('Bob the blob')->remove();
     * </code>
     *
     * @throws QdbAliasNotFoundException
     */
    function remove();

    /**
     * Removes a tag from the entry.
     *
     * @example
     * <code>
     * $cluster->blob('Bob the blob')->removeTag('stupid name');
     * </code>
     *
     * @param QdbTag|string $tag The tag to the entry, or its alias.
     * @return bool `true` if the tag was added, or `false` it the entry already had this tag.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     */
    function removeTag($tag);
}
?>
