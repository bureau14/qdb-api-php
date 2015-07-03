<?php
/**
 * A blob in the database.
 *
 * Blob stands for Binary Large Object, meaning that you can store arbitrary data in this blob.
 * @example
 * You get a `QdbBlob` instance by calling {@link \QdbCluster::blob}.
 * Then you can perform atomic operations on the blob:
 * <code>
 * $cluster->blob('key 0')->put('value 0');
 * $cluster->blob('key 1')->put('value 1');
 * $value2 = $cluster->blob('key 2')->get();
 * </code>
 */
class QdbBlob extends QdbExpirableEntry
{
    /**
     * Atomically compares the blob's content with `$comparand` and updates it to `$new_content` if, and only if, they match.
     * @param string $new_content The new content of the blob in case of a match.
     * @param string $comparand The content to be compared to.
     * @param int $expiry_time The absolute expiration time, in seconds since epoch (`0` means "never expires").
     * @return string The original value of the blob.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     * @example
     * <code>
     * $oldContent = $cluster->blob('alias')->compareAndSwap('newContent', 'comparand');
     * </code>
     */
    function compareAndSwap($new_content, $comparand, $expiry_time);

    /**
     * Reads the blob's content.
     * @return string The blob's content.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     * @example
     * <code>
     * $content = $cluster->blob('alias')->get();
     * </code>
     */
    function get();

    /**
     * Atomically gets blob's content and removes it.
     * @return string The blob's content.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     * @example
     * <code>
     * $content = $cluster->blob('alias')->getAndRemove();
     * </code>
     */
    function getAndRemove();

    /**
     * Atomically gets the blob's content and replaces it.
     * @param string $content The new content of the blob.
     * @param int $expiry_time The absolute expiration time, in seconds since epoch (`0` means "never expires").
     * @return string The blob's content.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     * @example
     * <code>
     * $oldContent = $cluster->blob('alias')->getAndUpdate('newContent');
     * </code>
     */
    function getAndUpdate($content, $expiry_time=0);

    /**
     * Sets the blob's content, fails if it already exists.
     * @param string $content The initial content of the blob.
     * @param int $expiry_time The absolute expiration time, in seconds since epoch (`0` means "never expires").
     * @throws QdbAliasAlreadyExistsException
     * @throws QdbIncompatibleTypeException
     * @example
     * <code>
     * $cluster->blob('alias')->put('content');
     * </code>
     */
    function put($content, $expiry_time=0);

    /**
     * Removes the blob if content matches.
     * @param string $comparand The content to be compared to.
     * @return bool `true` if the blob was actually removed; `false` if not.
     * @throws QdbAliasNotFoundException
     * @throws QdbIncompatibleTypeException
     * @example
     * <code>
     * $removed = $cluster->blob('alias')->removeIf('comparand');
     * </code>
     */
    function removeIf();

    /**
     * Sets the blob's content, creates the blob if needed.
     * @param string $content The new content of the blob.
     * @param int $expiry_time The absolute expiration time, in seconds since epoch (`0` means "never expires").
     * @throws QdbIncompatibleTypeException
     * @example
     * <code>
     * $cluster->blob('alias')->update('content');
     * </code>
     */
    function update($content, $expiry_time);
}
?>