The `QdbInteger` class
======================

Represents an signed 64-bit integer in a *quasardb* database.

Example
-------

You get a `QdbInteger` instance by calling `QdbCluster::integer()`.
Then you can perform atomic operation on the integer:

    $cluster->integer('key 0')->put(1);
    $cluster->integer('key 1')->update(2);
    $value2 = $cluster->integer('key 2')->get();

Class synopsis
--------------

    QdbInteger
    {
        void add ( int $value )
        string alias ( )
        void expiresAt ( int $expiry_time )
        void expiresFromNow ( int $time_delta )
        int get ( )
        int getExpiryTime ( )
        void put ( int $value [, int $expiry_time = 0 ])
        void remove ( )
        void update ( int $value [, int $expiry_time = 0 ])
    }


`QdbInteger`'s methods
--------------------


### `int QdbInteger::add ( int $value )`

###### Description
Atomically increment the value in the database.

###### Parameters
- `$value`: the value to add to the integer in the database

###### Returns
The new value (i.e. after the addition) of the integer.

###### Exceptions
- `QdbAliasNotFoundException` if the entry does not exist.
- `QdbIncompatibleTypeException` if the entry is not an integer (could be a blob for instance)


### `string QdbInteger::alias ( )`

###### Description
Gets the alias (i.e. its "key") of the integer in the database.

###### Parameters
None.

###### Returns
The alias.


### `void QdbInteger::expiresAt ( int $expiry_time )`

###### Description
Sets the expiry time of an existing entry. An `$expiry_time` of zero means the entry never expires.

###### Parameters
- `$expiry_time`: absolute time after which the entry expires, in seconds, relative to epoch.

###### Returns
Nothing.

###### Exceptions
- `QdbAliasNotFoundException` if the entry does not exist.
- `QdbInvalidArgumentException` if the expiry time is in the past


### `void QdbInteger::expiresFromNow ( int $time_delta )`

###### Description
Sets the expiry time of an existing entry. An `$time_delta` of zero means the entry expires as soon as possible.

###### Parameters
- `$time_delta`:  time in seconds, relative to the call time, after which the entry expires.

###### Returns
Nothing.

###### Exceptions
- `QdbAliasNotFoundException` if the entry does not exist.
- `QdbInvalidArgumentException` if the expiry time is in the past


### `int QdbInteger::get ( )`

###### Description
Retrieves an entry's value.

###### Parameters
None.

###### Returns
The value of the integer.

###### Exceptions
- `QdbAliasNotFoundException` if the entry does not exist.
- `QdbIncompatibleTypeException` if the entry is not an integer (could be a blob for instance)


### `int QdbInteger::getExpiryTime ( )`

###### Description
Retrieves the expiry time of an existing entry. A value of zero means the entry never expires.

###### Parameters
None.

###### Returns
The absolute expiry time, in seconds since epoch.

###### Exceptions
- `QdbAliasNotFoundException` if the entry does not exist.


### `void QdbInteger::put ( string $value [, int $expiry_time = 0 ])`

###### Description
Adds an entry.

Alias beginning with "qdb" are reserved and cannot be used.

###### Parameters
- `$value`: a string representing the entry’s content to be added.
- `$expiry_time`: the absolute expiry time of the entry, in seconds, relative to epoch

###### Returns
Nothing.

###### Exceptions
- `QdbAliasAlreadyExistsException` if the entry already exists.


### `void QdbInteger::remove ( )`

###### Description
Removes the integer from the database.

###### Parameters
None.

###### Returns
Nothing.

###### Exceptions
- `QdbAliasNotFoundException` if the entry does not exist.


### `void QdbInteger::update ( int $value [, int $expiry_time = 0 ])`

###### Description
Updates an entry.
Alias beginning with "qdb" are reserved and cannot be used.

###### Parameters
- `$value`: the new value
- `$expiry_time`: the absolute expiry time of the entry, in seconds, relative to epoch

###### Returns
Nothing.
