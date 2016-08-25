<?php
/**
 * Welcome to quasardb API for PHP
 *
 * Interfacing with a quasardb database from a PHP program is extremely straightforward, just create a {@link QdbCluster} and perform the operations.
 * <code>
 * $cluster = new QdbCluster('qdb://127.0.0.1:2836');
 * </code>
 *
 * OK, now that we have a connection to the database, let's store some binary data::
 * <code>
 * $blob = $cluster->blob('Bob the blob');
 *
 * $blob->put('firstValue');
 * $blob->update('secondValue');
 * $value = $blob->get();
 * </code>
 *
 * Quasardb provides concurrent double-ended queue, or "deque"
 * <code>
 * $queue = $cluster->deque('Andrew the queue');
 *
 * $queue->pushBack('firstValue');
 * $queue->pushBack('secondValue');
 * $value = $queue->popFront();
 * </code>
 *
 * quasardb comes out of the box with server-side atomic integers:
 * <code>
 * $integer = $cluster->integer('Roger the integer');
 *
 * $integer->put(42);
 * $total = $integer->add(12);
 * </code>
 *
 * We also provide distributed sets.
 * <code>
 * $set = $cluster->hashSet('Janet the set');
 *
 * $set->insert('value');
 * $set->erase('value');
 * $hasValue = $set->contains('value');
 * </code>
 *
 * Here's how you can easily find your data, using tags:
 * <code>
 * $cluster->blob('Bob the blob')->attachTag('Male');
 * $cluster->integer('Roger the integer')->attachTag('Male');
 *
 * $males = $cluster->tag('Male')->getEntries();
 * </code>
 *
 * The source code of this library can by found on <a href='https://github.com/bureau14/qdb-api-php'>GitHub</a>.
 * @see QdbBlob, QdbHashSet, QdbInteger, QdbDeque, QdbTag
 */
function Introduction();