# quasardb PHP API

## Introduction

Using *quasardb* cluster from a PHP program is extremely straightforward, just create a `QdbCluster` and perform the operations.

    $nodes = array(array('address' => '127.0.0.1', 'port' => 2836));

    $cluster = new QdbCluster($nodes);
    $cluster->put('key 0', 'value 0');
    $cluster->put('key 1', 'value 1');
    $value2 = $cluster->get('key 2');

Not fast enough? Try the `QdbBatch` class:

    $batch = new QdbBatch();
    $batch->put('key 0', 'value 0');
    $batch->put('key 1', 'value 1');
    $batch->get('key 2');
    
    $result = $cluster->runBatch($batch);

    $value2 = $result[2];

This will reduce the number of network request and it will be faster by orders of magnitudes.

## Installation

### Installation on Linux

#### Assumptions:

1. `php` and `php-devel` are installed
2. `qdb-capi` is installed in `/path/to/qdb_capi`
3. `qdb-php-api.tar.gz` has been downloaded

Please adapt to your configuration.

#### Instructions:

    tar xvf qdb-php-api.tar.gz
    cd qdb-php-api
    phpize
    ./configure --with-qdb=/path/to/qdb_capi
    make
    make install    

### Installation on Windows

#### Assumptions:

1. Visual Studio is installed
2. [PHP source code](http://windows.php.net/download/) is decompressed in `C:\php-src\`
3. `qdb-capi` is installed in `C:\qdb-capi`
4. `qdb-php-api.tar.gz` has been decompressed in `C:\php-src\ext\qdb`

Please adapt to your configuration.

#### Instructions:

Open a *Visual Studio Developer Command Prompt* (either x86 or x86) and type:

    cd /d C:\php-src\
    buildconf
    configure --with-qdb=C:\qdb-capi
    nmake
    nmake install

You may want to customize `configure`'s flags, for instance `--enable-zts` or `--disable-zts` to control thread-safety.

Also if `qdb_api.dll` is not available on the `PATH`, you'll need to copy it to `C:\php\`. 

## Runtime configuration

The following settings can be changed in `php.ini`.

##### `qdb.log_level`

Specifies the log verbosity.

Allowed values are `detailed`, `debug`, `info`, `warning`, `error`, `panic`. The default is `panic`.

## Reference

### The `QdbBatch` class

Represents a collection of operation that can be executed with a single query.

Operation are executed by a call to `QdbCluster::runBatch ( $batch )`

#### Example

    $batch = new QdbBatch();
    $batch->put('key 0', 'value 0');
    $batch->put('key 1', 'value 1');
    $batch->get('key 2');
    
    $result = $cluster->runBatch($batch);

    $value2 = $result[2];
    

#### Class synopsis

    QdbBatch
    {
        __construct ( void )
        void compareAndSwap ( string $alias , string $new_content , string $comparand [, int $expiry_time = 0 ] )
        void get ( string $alias )
        void getRemove ( string $alias )
        void getUpdate ( string $alias , string $content [, int $expiry_time = 0 ] )
        void put ( string $alias , string $content [, int $expiry_time = 0 ] )
        void remove ( string $alias )
        void removeIf ( string $alias , string $comparand )
        void update ( string $alias , string $content [, int $expiry_time = 0 ] )
    }


#### `QdbBatch`'s methods


##### `QdbBatch::__construct ( void )`

###### Description
Creates an empty batch, i.e. an empty collection of operation.

Batch operations can greatly increase performance when it is necessary to run many small operations.
Operations in a `QdbBatch` are not executed until `QdbCluster::runBatch ( $batch )` is called.


##### `void QdbBatch::compareAndSwap ( string $alias , string $new_content , string $comparand [, int $expiry_time = 0 ] )`

###### Description
Adds a "compare and swap" operation to the batch.
When executed, the "compare and swap" operation atomically compares the entry with `$comparand` and updates it to `$new_content` if, and only if, they match.

###### Parameters
- `$alias`: a string representing the entry’s alias to compare to.
- `$new_content`: a string representing the entry’s content to be updated in case of match.
- `$comparand`: a string representing the entry’s content to be compared to.

###### Returns
The original value of the entry is stored in the array returned by `QdbCluster::runBatch ( $batch )`.

###### Exceptions
If the entry does not exist, a `QdbAliasNotFoundException` will be thrown when reading the value.


##### `void QdbBatch::get ( string $alias )`

###### Description
Adds a "get" operation to the batch.
When executed, the "get" operation retrieves an entry's content.

###### Parameters
- `$alias`: a string representing the entry’s alias whose content is to be retrieved.

###### Returns
The value of the entry is stored in the array returned by `QdbCluster::runBatch ( $batch )`.

###### Exceptions
If the entry does not exist, a `QdbAliasNotFoundException` will be thrown when reading the value.


##### `void QdbBatch::getRemove ( string $alias )`

###### Description
Adds a "get and remove" operation to the batch.
When executed, the "get and remove" operation atomically gets an entry and removes it.

###### Parameters
- `$alias`: a string representing the entry’s alias to delete.

###### Returns
The content of the entry is stored in the array returned by `QdbCluster::runBatch ( $batch )`.

###### Exceptions
If the entry does not exist, a `QdbAliasNotFoundException` will be thrown when reading the content.


##### `void QdbBatch::getUpdate ( string $alias , string $content [, int $expiry_time = 0 ] )`

###### Description
Adds a "get and update" operation to the batch.
When executed, the "get and update" operation atomically gets and updates (in this order) the entry.

###### Parameters
- `$alias`: a string representing the entry’s alias to update.
- `$content`: a string representing the entry’s content to be set.
- `$expiry_time`: the absolute expiry time of the entry, in seconds, relative to epoch.

###### Returns
The content of the entry (before the update) is stored in the array returned by `QdbCluster::runBatch ( $batch )`.

###### Exceptions
If the entry does not exist, a `QdbAliasNotFoundException` will be throw when reading the value.


##### `void QdbBatch::put ( string $alias , string $content [, int $expiry_time = 0 ] )`

###### Description
Adds a "put" operation to the batch.
When executed, the "put" operation adds an entry.

Alias beginning with "qdb" are reserved and cannot be used.

###### Parameters
- `$alias`: a string string representing the entry’s alias to create.
- `$content`: a string representing the entry’s content to be added.
- `$expiry_time`: the absolute expiry time of the entry, in seconds, relative to epoch

###### Exceptions
If the entry already exists, the operation will fail and a `QdbAliasAlreadyExistsException` will be thrown when reading the matching item of the array returned by `QdbCluster::runBatch ( $batch )`.


##### `void QdbBatch::remove ( string $alias )`

###### Description
Adds a "remove" operation to the batch.
When executed, the "remove" operation removes an entry.

###### Parameters
- `$alias`: a string representing the entry’s alias to delete.

###### Exceptions
If the entry does not exist, the operation will fail and a `QdbAliasNotFoundException` will be thrown when reading the matching item of the array returned by `QdbCluster::runBatch ( $batch )`.


##### `void QdbBatch::removeIf ( string $alias , string $comparand )`

###### Description
Adds a "remove if" operation to the batch.
When executed, the "remove if" operation removes an entry if it matches `$comparand`. The operation is atomic. 

###### Parameters
- `$alias`: a string representing the entry’s alias to delete.
- `$comparand`: a string representing the entry’s content to be compared to.

###### Returns
The result of the operation, `true` if the entry was actually removed or `false` otherwise, is stored in the array returned by `QdbCluster::runBatch ( $batch )`.

###### Exceptions
If the entry does not exist, the operation will fail and a `QdbAliasAlreadyExistsException` will be throw when reading the matching item of the array returned by `QdbCluster::runBatch ( $batch )`.


##### `void QdbBatch::update ( string $alias , string $content [, int $expiry_time = 0 ] )`

###### Description
Adds an "update" operation to the batch.
When executed, the "update" operation updates an entry. If the entry already exists, the content will be updated. If the entry does not exist, it will be created.

Alias beginning with "qdb" are reserved and cannot be used.

###### Parameters
- `$alias`: a string representing the entry’s alias to update.
- `$content`: a string representing the entry’s content to be added.
- `$expiry_time`: the absolute expiry time of the entry, in seconds, relative to epoch



### The QdbCluster class

Represents a connection to a *quasardb* cluster.

#### Example

    $nodes = array(array('address' => '127.0.0.1', 'port' => 2836));

    $cluster = new QdbCluster($nodes);
    $cluster->put('key 0', 'value 0');
    $cluster->put('key 1', 'value 1');
    $value2 = $cluster->get('key 2');

#### Class synopsis

    QdbCluster
    {
        __construct ( array $nodes )
        string compareAndSwap ( string $alias , string $new_content , string $comparand [, int $expiry_time = 0 ] )
        void expiresAt ( string $alias , int $expiry_time )
        void expiresFromNow ( string $alias , int $time_delta )
        string get ( string $alias )
        int getExpiryTime ( string $alias )
        void getRemove ( string $alias )
        string getUpdate ( string $alias , string $content [, int $expiry_time = 0 ] )
        void put ( string $alias , string $content [, int $expiry_time = 0 ])
        void remove ( string $alias)
        bool removeIf ( string $alias, string $comparand )
        array runBatch ( QdbBatch $batch )
        void update ( string $alias , string $content [, int $expiry_time = 0 ])
    }


#### QdbCluster's methods


##### `QdbCluster::__construct ( array $nodes )`

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


##### `string QdbCluster::compareAndSwap ( string $alias , string $new_content , string $comparand [, int $expiry_time = 0 ] )`

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


##### `void QdbCluster::expiresAt ( string $alias , int $expiry_time )`

###### Description
Sets the expiry time of an existing entry. An `$expiry_time` of zero means the entry never expires.

###### Parameters
- `$alias`: a string representing the entry’s alias for which the expiry must be set.
- `$expiry_time`: absolute time after which the entry expires, in seconds, relative to epoch.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.
        

##### `void QdbCluster::expiresFromNow ( string $alias , int $time_delta )`

###### Description
Sets the expiry time of an existing entry. An `$expiry_time` of zero means the entry expires as soon as possible.

###### Parameters
- `$alias`: a string representing the entry’s alias for which the expiry must be set.
- `$expiry_time`:  time in seconds, relative to the call time, after which the entry expires.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.
      

##### `string QdbCluster::get ( string $alias )`

###### Description
Retrieves an entry's content.

###### Parameters
- `$alias`: a string representing the entry’s alias whose content is to be retrieved.

###### Returns
A string representing the entry’s content.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.
        

##### `int QdbCluster::getExpiryTime ( string $alias )`

###### Description
Retrieves the expiry time of an existing entry. A value of zero means the entry never expires.

###### Parameters
- `$alias`: a string representing the entry’s alias for which the expiry must be get.

###### Returns
The receive the absolute expiry time, in seconds since epoch.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.


##### `void QdbCluster::getRemove ( string $alias )`

###### Description
Atomically gets an entry and removes it.

###### Parameters
- `$alias`: a string representing the entry’s alias to delete.

###### Returns
A string representing the entry’s content.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.
        

##### `string QdbCluster::getUpdate ( string $alias , string $content [, int $expiry_time = 0 ] )`

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
        

##### `void QdbCluster::put ( string $alias , string $content [, int $expiry_time = 0 ])`

###### Description
Adds an entry. 

Alias beginning with "qdb" are reserved and cannot be used.

###### Parameters
- `$alias`: a string string representing the entry’s alias to create.
- `$content`: a string representing the entry’s content to be added.
- `$expiry_time`: the absolute expiry time of the entry, in seconds, relative to epoch

###### Exceptions
Throws a `QdbAliasAlreadyExistsException` if the entry already exists.

    
##### `void QdbCluster::remove ( string $alias)`

###### Description
Removes an entry.

###### Parameters
- `$alias`: a string representing the entry’s alias to delete.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.


##### `bool QdbCluster::removeIf ( string $alias, string $comparand )`

###### Description
Removes an entry if it matches `$comparand`.

###### Parameters
- `$alias`: a string representing the entry’s alias to delete.
- `$comparand`: a string representing the entry’s content to be compared to.

###### Returns
`true` if the entry was actually removed, `false` if not.

###### Exceptions
Throws a `QdbAliasNotFoundException` if the entry does not exist.
     
   
##### `array QdbCluster::runBatch ( QdbBatch $batch )`

###### Description
Executes operations of a `QdbBatch`.

###### Parameters
- `$batch`: a `QdbBatch` contains the operations to be performed.

###### Returns
Returns an array (more exactly a class `QdbBatchResult` that behaves like an array) with the operation results.
Operations results are stored in the order in which operations have been added to the `QdbBatch`, which is not necessarily the order in which operation are executed in the cluster.

###### Exceptions
An exception related to an operation will be thrown when reading the matching item from the returned array.  
    
    
##### `void QdbCluster::update ( string $alias , string $content [, int $expiry_time = 0 ])`

###### Description
Updates an entry.
Alias beginning with "qdb" are reserved and cannot be used.

###### Parameters
- `$alias`: a string representing the entry’s alias to update.
- `$content`: a string representing the entry’s content to be added.
- `$expiry_time`: the absolute expiry time of the entry, in seconds, relative to epoch