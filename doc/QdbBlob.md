The `QdbBlob` class
======================

Represents a binary entry in a *quasardb* database.
Blob stands for Binary Large Object, meaning that you can store arbitrary data in this entry.

Example
-------

You get a `QdbBlob` instance by calling `QdbCluster::blob()`.
Then you can perform atomic operation on the blob:

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
        int getExpiryTime ( )
        void getRemove ( )
        string getUpdate ( string $content [, int $expiry_time = 0 ] )
        void put ( string $content [, int $expiry_time = 0 ])
        void remove ( )
        bool removeIf ( string $comparand )
        void update ( string $content [, int $expiry_time = 0 ])
    }


QdbBlob's methods
--------------------


### `string QdbQueue::alias ( )`

###### Description
Gets the alias (i.e. its "key") of the blob in the database.

###### Returns
The alias.


### `string QdbBlob::compareAndSwap ( string $new_content , string $comparand [, int $expiry_time = 0 ] )`

###### Description
Atomically compares the entry with `$comparand` and updates it to `$new_content` if, and only if, they match.

###### Parameters
- `$new_content`: a string representing the entry’s content to be updated in case of match.
- `$comparand`: a string representing the entry’s content to be compared to.

###### Returns
Always returns the original value of the entry.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.


### `void QdbBlob::expiresAt ( int $expiry_time )`

###### Description
Sets the expiry time of an existing entry. An `$expiry_time` of zero means the entry never expires.

###### Parameters
- `$expiry_time`: absolute time after which the entry expires, in seconds, relative to epoch.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.


### `void QdbBlob::expiresFromNow ( int $time_delta )`

###### Description
Sets the expiry time of an existing entry. An `$expiry_time` of zero means the entry expires as soon as possible.

###### Parameters
- `$expiry_time`:  time in seconds, relative to the call time, after which the entry expires.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.


### `string QdbBlob::get ( )`

###### Description
Retrieves an entry's content.

###### Parameters
None.

###### Returns
A string representing the entry’s content.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.


### `int QdbBlob::getExpiryTime ( )`

###### Description
Retrieves the expiry time of an existing entry. A value of zero means the entry never expires.

###### Parameters
None.

###### Returns
The receive the absolute expiry time, in seconds since epoch.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.


### `void QdbBlob::getRemove ( )`

###### Description
Atomically gets an entry and removes it.

###### Parameters
None.

###### Returns
A string representing the entry’s content.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.


### `string QdbBlob::getUpdate ( string $content [, int $expiry_time = 0 ] )`

###### Description
Atomically gets and updates (in this order) the entry.

###### Parameters
- `$content`: a string representing the entry’s content to be set.
- `$expiry_time`: the absolute expiry time of the entry, in seconds, relative to epoch.

###### Returns
A string representing the entry’s content, before the update.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.


### `void QdbBlob::put ( string $content [, int $expiry_time = 0 ])`

###### Description
Adds an entry.

Alias beginning with "qdb" are reserved and cannot be used.

###### Parameters
- `$content`: a string representing the entry’s content to be added.
- `$expiry_time`: the absolute expiry time of the entry, in seconds, relative to epoch

###### Exceptions
Throws a `QdbAliasAlreadyExistsException` if the entry already exists.


### `void QdbBlob::remove ( )`

###### Description
Removes an entry.

###### Parameters
None.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.


### `bool QdbBlob::removeIf ( string $comparand )`

###### Description
Removes an entry if it matches `$comparand`.

###### Parameters
- `$comparand`: a string representing the entry’s content to be compared to.

###### Returns
`true` if the entry was actually removed, `false` if not.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.


### `void QdbBlob::update ( string $content [, int $expiry_time = 0 ])`

###### Description
Updates an entry.
Alias beginning with "qdb" are reserved and cannot be used.

###### Parameters
- `$content`: a string representing the entry’s content to be added.
- `$expiry_time`: the absolute expiry time of the entry, in seconds, relative to epoch