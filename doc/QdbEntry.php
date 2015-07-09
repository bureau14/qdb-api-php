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