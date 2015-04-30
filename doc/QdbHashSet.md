The `QdbHashSet` class
====================

Represents an unordered set of blob in the *quasardb* database.

Example
-------

You get a `QdbHashSet` instance by calling `QdbCluster::hashSet()`.
Then you can perform atomic operations on the set:

    $hashSet = $cluster->hashSet('my hashSet');
    $hashSet->insert('value');
    $hasValue = $hashSet->contains('value');

Class synopsis
--------------

    QdbHashSet
    {
        string alias ( )
        bool contains ( string $value )
        bool erase ( string $value )
        bool insert ( string $value )
    }


QdbHashSet's methods
------------------


### `string QdbHashSet::alias ( )`

###### Description
Gets the alias (i.e. its "key") of the set in the database.

###### Parameters
None.

###### Returns
The alias.


### `bool QdbHashSet::contains ( string $value )`

###### Description
Tells if the value is present in the set.

###### Parameters
- `$value`: the value to look for.

###### Returns
- `true` if the value is present in the set
- `false` if not.

###### Exceptions
- `QdbAliasNotFoundException` if the set doesn't exist.
- `QdbIncompatibleTypeException` if the entry is not a set.


### `bool QdbHashSet::erase ( string $value )`

###### Description
Removes the value from the set.

###### Parameters
- `$value`: the value to remove.

###### Returns
- `true` if the value was present in the set
- `false` if not.

###### Exceptions
- `QdbAliasNotFoundException` if the set doesn't exist.
- `QdbIncompatibleTypeException` if the entry is not a set.


### `bool QdbHashSet::insert ( string $value )`

###### Description
Adds the specified value  in the set.

###### Parameters
- `$value`: the value to add.

###### Returns
- `true` if the value was added
- `false` if it was already present in the set.

###### Exceptions
- `QdbAliasNotFoundException` if the set doesn't exist.
- `QdbIncompatibleTypeException` if the entry is not a set.
