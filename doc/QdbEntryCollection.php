<?php
namespace qdb;
/**
 * A traversable collection of {@link QdbEntry}.
 *
 * A `QdbEntryCollection` is returned by {@link \QdbTag::getEntries()}.
 *
 * Entries in the collection have a concrete type like {@link QdbBlob}, {@link QdbInteger}, or {@link QdbTag}
 *
 * @example
 * <code>
 * $males = $cluster->tag('male')->getEntries();
 * foreach ($males as $entry) {
 *     echo($entry->alias() . ' is a male');
 * }
 * </code>
 *
 * @see QdbTag::getEntries()
 */
class QdbEntryCollection implements Iterator
{
}
?>
