<?php
/**
 * A traversable collection of {@link QdbTag}.
 *
 * A `QdbTagCollection` is returned by {@link \QdbEntry::getTags()}.
 *
 * @example
 * <code>
 * $bobsTags = $cluster->blob('Bob the blob')->getTags();
 * foreach ($bobsTags as $tag) {
 *     echo('Bob has the tag ' . $tag->alias());
 * }
 * </code>
 *
 * @see QdbEntry::getTags()
 */
class QdbTagCollection implements Iterator
{
}
?>