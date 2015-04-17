The `QdbCluster` class
======================

Represents a connection to a *quasardb* cluster.

Example
-------

    $nodes = array(array('address' => '127.0.0.1', 'port' => 2836));

    $cluster = new QdbCluster($nodes);
    $cluster->put('key 0', 'value 0');
    $cluster->put('key 1', 'value 1');
    $value2 = $cluster->get('key 2');

Class synopsis
--------------

    QdbCluster
    {
        __construct ( array $nodes )
        string compareAndSwap ( string $alias , string $new_content , string $comparand [, int $expiry_time = 0 ] )
        void expiresAt ( string $alias , int $expiry_time )
        void expiresFromNow ( string $alias , int $time_delta )
        string get ( string $alias )
        int getExpiryTime ( string $alias )
        QdbQueue getQueue ( string $alias )
        void getRemove ( string $alias )
        string getUpdate ( string $alias , string $content [, int $expiry_time = 0 ] )
        void put ( string $alias , string $content [, int $expiry_time = 0 ])
        void remove ( string $alias)
        bool removeIf ( string $alias, string $comparand )
        array runBatch ( QdbBatch $batch )
        void update ( string $alias , string $content [, int $expiry_time = 0 ])
    }


QdbCluster's methods
--------------------


### `QdbCluster::__construct ( array $nodes )`

###### Description
Connects to a *quasardb* cluster through the specified nodes.

###### Parameters
`$nodes` is an array of array:

    $nodes = array(
        array('address'=>'192.168.0.1','port'=>'2836'),
        array('address'=>'192.168.0.2','port'=>'2836')
    );

###### Exceptions
Throws a `QdbClusterConnectionFailedException` if the connection **to every node** fails.


### `string QdbCluster::compareAndSwap ( string $alias , string $new_content , string $comparand [, int $expiry_time = 0 ] )`

###### Description
Atomically compares the entry with `$comparand` and updates it to `$new_content` if, and only if, they match.

###### Parameters
- `$alias`: a string representing the entry’s alias to compare to.
- `$new_content`: a string representing the entry’s content to be updated in case of match.
- `$comparand`: a string representing the entry’s content to be compared to.

###### Returns
Always returns the original value of the entry.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.


### `void QdbCluster::expiresAt ( string $alias , int $expiry_time )`

###### Description
Sets the expiry time of an existing entry. An `$expiry_time` of zero means the entry never expires.

###### Parameters
- `$alias`: a string representing the entry’s alias for which the expiry must be set.
- `$expiry_time`: absolute time after which the entry expires, in seconds, relative to epoch.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.
        

### `void QdbCluster::expiresFromNow ( string $alias , int $time_delta )`

###### Description
Sets the expiry time of an existing entry. An `$expiry_time` of zero means the entry expires as soon as possible.

###### Parameters
- `$alias`: a string representing the entry’s alias for which the expiry must be set.
- `$expiry_time`:  time in seconds, relative to the call time, after which the entry expires.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.
      

### `string QdbCluster::get ( string $alias )`

###### Description
Retrieves an entry's content.

###### Parameters
- `$alias`: a string representing the entry’s alias whose content is to be retrieved.

###### Returns
A string representing the entry’s content.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.


### `QdbQueue QdbCluster::getQueue ( string $alias )`

###### Description
Creates a `QdbQueue` associated with the specified alias.
No query is performed at this point.

###### Parameters
- `$alias`: the alias of the queue in the database.

###### Returns
The `QdbQueue`.

###### Exceptions
None.
        

### `int QdbCluster::getExpiryTime ( string $alias )`

###### Description
Retrieves the expiry time of an existing entry. A value of zero means the entry never expires.

###### Parameters
- `$alias`: a string representing the entry’s alias for which the expiry must be get.

###### Returns
The receive the absolute expiry time, in seconds since epoch.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.


### `void QdbCluster::getRemove ( string $alias )`

###### Description
Atomically gets an entry and removes it.

###### Parameters
- `$alias`: a string representing the entry’s alias to delete.

###### Returns
A string representing the entry’s content.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.
        

### `string QdbCluster::getUpdate ( string $alias , string $content [, int $expiry_time = 0 ] )`

###### Description
Atomically gets and updates (in this order) the entry.

###### Parameters
- `$alias`: a string representing the entry’s alias to update.
- `$content`: a string representing the entry’s content to be set.
- `$expiry_time`: the absolute expiry time of the entry, in seconds, relative to epoch.

###### Returns
A string representing the entry’s content, before the update.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.
        

### `void QdbCluster::put ( string $alias , string $content [, int $expiry_time = 0 ])`

###### Description
Adds an entry. 

Alias beginning with "qdb" are reserved and cannot be used.

###### Parameters
- `$alias`: a string string representing the entry’s alias to create.
- `$content`: a string representing the entry’s content to be added.
- `$expiry_time`: the absolute expiry time of the entry, in seconds, relative to epoch

###### Exceptions
Throws a `QdbAliasAlreadyExistsException` if the entry already exists.

    
### `void QdbCluster::remove ( string $alias)`

###### Description
Removes an entry.

###### Parameters
- `$alias`: a string representing the entry’s alias to delete.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.


### `bool QdbCluster::removeIf ( string $alias, string $comparand )`

###### Description
Removes an entry if it matches `$comparand`.

###### Parameters
- `$alias`: a string representing the entry’s alias to delete.
- `$comparand`: a string representing the entry’s content to be compared to.

###### Returns
`true` if the entry was actually removed, `false` if not.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.
     
   
### `array QdbCluster::runBatch ( QdbBatch $batch )`

###### Description
Executes operations of a `QdbBatch`.

###### Parameters
- `$batch`: a `QdbBatch` contains the operations to be performed.

###### Returns
Returns an array (more exactly a class `QdbBatchResult` that behaves like an array) with the operation results.
Operations results are stored in the order in which operations have been added to the `QdbBatch`, which is not necessarily the order in which operation are executed in the cluster.

###### Exceptions
An exception related to an operation will be thrown when reading the matching item from the returned array.  
    
    
### `void QdbCluster::update ( string $alias , string $content [, int $expiry_time = 0 ])`

###### Description
Updates an entry.
Alias beginning with "qdb" are reserved and cannot be used.

###### Parameters
- `$alias`: a string representing the entry’s alias to update.
- `$content`: a string representing the entry’s content to be added.
- `$expiry_time`: the absolute expiry time of the entry, in seconds, relative to epoch