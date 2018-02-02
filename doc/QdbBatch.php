<?php
namespace qdb;
/**
 * A collection of operation that can be executed with a single query.
 *
 * Operation are executed by a call to {@link \QdbCluster::runBatch()}
 * @example
 * <code>
 * $batch = new QdbBatch();
 * $batch->put('key 0', 'value 0');
 * $batch->put('key 1', 'value 1');
 * $batch->get('key 2');
 *
 * $result = $cluster->runBatch($batch);
 *
 * $value2 = $result[2];
 * </code>
 */
class QdbBatch
{
    /**
     * Creates an empty batch, i.e. an empty collection of operation.
     */
    function __construct();

    /**
     * Adds a "compare and swap" operation to the batch.
     *
     * When executed, the "compare and swap" operation atomically compares a blob with `$comparand` and updates it to `$new_content` if, and only if, they match.
     *
     * @param string $alias The blob's alias.
     * @param string $new_content The new content of the blob in case of match.
     * @param string $comparand The content to be compared to.
     * @param int $expiry_time The absolute expiration time, in seconds since epoch (`0` means "never expires").
     * @see QdbBlob::compareAndSwap()
     */
    function compareAndSwap($alias, $new_content, $comparand, $expiry_time=0);

    /**
     * Adds a "get" operation to the batch.
     *
     * When executed, the "get" operation reads the content of a blob.
     *
     * @param string $alias The blob's alias.
     * @see QdbBlob::get()
     */
    function get($alias);

    /**
     * Adds a "get and remove" operation to the batch.
     *
     * When executed, the "get and remove" operation atomically reads a blob and removes it.
     *
     * @param string $alias The blob's alias.
     * @see QdbBlob::getAndRemove()
     */
    function getAndRemove($alias);

    /**
     * Adds a "get and remove" operation to the batch.
     *
     * When executed, the "get and remove" operation atomically reads a blob's content and replaces it.
     *
     * @param string $alias The blob's alias.
     * @param string $content The new content of the blob.
     * @param int $expiry_time The absolute expiration time, in seconds since epoch (`0` means "never expires").
     * @see QdbBlob::getAndUpdate()
     */
    function getAndUpdate($alias, $content, $expiry_time=0);

    /**
     * Adds a "put" operation to the batch.
     *
     * When executed, the "put" operation creates a blob.
     *
     * @param string $alias The blob's alias.
     * @param string $content The initial content of the blob.
     * @param int $expiry_time The absolute expiration time, in seconds since epoch (`0` means "never expires").
     * @see QdbBlob::put()
     */
    function put($alias, $content, $expiry_time=0);

    /**
     * Adds a "remove" operation to the batch.
     * When executed, the "remove" operation removes an entry.
     *
     * @param string $alias The entry's alias.
     * @see QdbEntry::remove()
     */
    function remove($alias);

    /**
     * Adds a "remove if" operation to the batch.
     *
     * When executed, the "remove if" operation removes a blob if it matches `$comparand`. The operation is atomic.
     *
     * @param string $alias The blob's alias.
     * @param string $comparand The content to be compared to.
     * @see QdbBlob::removeIf()
     */
    function removeIf($alias, $comparand);

    /**
     * Adds an "update" operation to the batch.
     *
     * When executed, the "update" operation sets the content of a blob. It will create the blob if needed.
     *
     * @param string $alias The blob's alias.
     * @param string $content The new content of the blob.
     * @param int $expiry_time The absolute expiration time, in seconds since epoch (`0` means "never expires").
     * @see QdbBlob::update()
     */
    function update($alias, $content, $expiry_time=0);
}
?>
