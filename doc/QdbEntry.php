<?php
namespace qdb;
/**
 * An entry in the database
 */
abstract class QdbEntry
{
    /**
     * Attachs a tag to the entry
     *
     * @example
     * <code>
     * $cluster->blob('Bob the blob')->attachTag('male');
     * </code>
     *
     * @param QdbTag|string $tag The tag to add to the entry, or its alias.
     * @return bool `true` if the tag was added, or `false` it the entry already had this tag.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     */
    function attachTag($tag);

    /**
     * Attachs one or more tags to the entry
     *
     * @example
     * <code>
     * $cluster->blob('Bob the blob')->attachTags(array('male','funny'));
     * </code>
     *
     * @param array $tag An array of `string` or `QdbTag`.
     * @return void
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     */
    function attachTags($tags);

    /**
     * Gets the alias (i.e. its "key") of the entry.
     *
     * Alias starting with `qdb` are reserved.
     *
     * @return string The alias of the entry.
     */
    function alias();

    /**
     * Enumerates the tags attached to the entry.
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
     * Checks if a tag is attached to the entry.
     *
     * @param QdbTag|string $tag The tag to check for, or its alias.
     * @return bool `true` if the entry is tagged with the given tag, `false` otherwise.
     *
     * @example
     * <code>
     * $bob_is_male = $cluster->blob('Bob the Blob')->hasTag('male');
     * // same as $bob_is_male = $cluster->tag('male')->hasEntry('Bob the Blob');
     * </code>
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
     * Detaches a tag from the entry.
     *
     * @example
     * <code>
     * $cluster->blob('Bob the blob')->detachTag('stupid name');
     * </code>
     *
     * @param QdbTag|string $tag The tag to the entry, or its alias.
     * @return bool `true` if the tag was added, or `false` it the entry already had this tag.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     */
    function detachTag($tag);
}
?>
