The `QdbBlob` class
======================

Represents a blob in a *quasardb* database.
Blob stands for Binary Large Object, meaning that you can store arbitrary data in this blob.

Example
-------

You get a `QdbBlob` instance by calling `QdbCluster::blob()`.
Then you can perform atomic operations on the blob:

    $cluster->blob('key 0')->put('value 0');
    $cluster->blob('key 1')->put('value 1');
    $value2 = $cluster->blob('key 2')->get();

Class synopsis
--------------

    QdbBlob
    {
        string alias ( )
        string compareAndSwap ( string $new_content , string $comparand [, int $expiry_time = 0 ] )
        void expiresAt ( int $expiry_time )
        void expiresFromNow ( int $time_delta )
        string get ( )
        string getAndRemove ( )
        string getAndUpdate ( string $content [, int $expiry_time = 0 ] )
        int getExpiryTime ( )
        void put ( string $content [, int $expiry_time = 0 ])
        void remove ( )
        bool removeIf ( string $comparand )
        void update ( string $content [, int $expiry_time = 0 ])
    }


`QdbBlob`'s methods
--------------------


### `string QdbBlob::alias ( )`

###### Description
Gets the alias (i.e. its "key") of the blob in the database.

###### Parameters
None.

###### Returns
The alias.


### `string QdbBlob::compareAndSwap ( string $new_content , string $comparand [, int $expiry_time = 0 ] )`

###### Description
Atomically compares the blob's content with `$comparand` and updates it to `$new_content` if, and only if, they match.

###### Parameters
- `$new_content`: a string representing the blob’s content to be updated in case of match.
- `$comparand`: a string representing the blob’s content to be compared to.

###### Returns
Always returns the original value of the blob.

###### Exceptions
- `QdbAliasNotFoundException` if the blob does not exist.


### `void QdbBlob::expiresAt ( int $expiry_time )`

###### Description
Sets the expiry time of the blob. An `$expiry_time` of zero means the blob never expires.

###### Parameters
- `$expiry_time`: absolute time after which the blob expires, in seconds, relative to epoch.

###### Returns
Nothing.

###### Exceptions
- `QdbAliasNotFoundException` if the blob does not exist.


### `void QdbBlob::expiresFromNow ( int $time_delta )`

###### Description
Sets the expiry time of the blob. A `$time_delta` of zero means the blob expires as soon as possible.

###### Parameters
- `$time_delta`:  time in seconds, relative to the call time, after which the blob expires.

###### Returns
Nothing.

###### Exceptions
- `QdbAliasNotFoundException` if the blob does not exist.


### `string QdbBlob::get ( )`

###### Description
Retrieves the blob's content.

###### Parameters
None.

###### Returns
A string representing the blob’s content.

###### Exceptions
- `QdbAliasNotFoundException` if the blob does not exist.


### `string QdbBlob::getAndRemove ( )`

###### Description
Atomically gets blob's content and removes it.

###### Parameters
None.

###### Returns
A string representing the blob’s content.

###### Exceptions
- `QdbAliasNotFoundException` if the blob does not exist.


### `string QdbBlob::getAndUpdate ( string $content [, int $expiry_time = 0 ] )`

###### Description
Atomically gets and updates (in this order) the blob.

###### Parameters
- `$content`: a string representing the blob’s content to be set.
- `$expiry_time`: the absolute expiry time of the blob, in seconds, relative to epoch.

###### Returns
A string representing the blob’s content, before the update.

###### Exceptions
- `QdbAliasNotFoundException` if the blob does not exist.


### `int QdbBlob::getExpiryTime ( )`

###### Description
Retrieves the expiry time of the blob. A value of zero means the blob never expires.

###### Parameters
None.

###### Returns
The absolute expiry time, in seconds since epoch.

###### Exceptions
- `QdbAliasNotFoundException` if the blob does not exist.


### `void QdbBlob::put ( string $content [, int $expiry_time = 0 ])`

###### Description
Sets blob's content but fails if the blob already exists.
See also `update()`.

Alias beginning with "qdb" are reserved and cannot be used.

###### Parameters
- `$content`: a string representing the new blob’s content.
- `$expiry_time`: the absolute expiry time of the blob, in seconds, relative to epoch

###### Returns
Nothing.

###### Exceptions
- `QdbAliasAlreadyExistsException` if the blob already exists.


### `void QdbBlob::remove ( )`

###### Description
Removes the blob.

###### Parameters
None.

###### Returns
Nothing.

###### Exceptions
- `QdbAliasNotFoundException` if the blob does not exist.


### `bool QdbBlob::removeIf ( string $comparand )`

###### Description
Removes the blob if it's content matches `$comparand`.

###### Parameters
- `$comparand`: a string representing the blob’s content to be compared to.

###### Returns
`true` if the blob was actually removed, `false` if not.

###### Exceptions
- `QdbAliasNotFoundException` if the blob does not exist.


### `void QdbBlob::update ( string $content [, int $expiry_time = 0 ])`

###### Description
Updates the content of the blob.
Alias beginning with "qdb" are reserved and cannot be used.
See also `put()`

###### Parameters
- `$content`: a string representing the blob’s content to be added.
- `$expiry_time`: the absolute expiry time of the blob, in seconds, relative to epoch

###### Returns
Nothing.
