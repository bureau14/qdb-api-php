// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h
#include <spl/spl_iterators.h>
#include <zend_interfaces.h>

#include "QdbBlob.h"
#include "QdbQueue.h"
#include "QdbInteger.h"
#include "QdbHashSet.h"
#include "QdbTag.h"
#include "QdbEntryCollection.h"
#include "class_definition.h"
#include "exceptions.h"

#include <qdb/client.h>

#define class_name          QdbEntryCollection
#define class_storage       tag_collection_t
#define class_interfaces    1,spl_ce_Iterator


typedef struct {
    zend_object std;
    qdb_handle_t handle;
    const char ** entries;
    size_t entries_count;
    size_t current;
} tag_collection_t;

extern zend_class_entry* ce_QdbEntryCollection;


void QdbEntryCollection_createInstance(zval* destination, qdb_handle_t handle, const char ** entries, size_t entries_count TSRMLS_DC)
{
    tag_collection_t* this;

    object_init_ex(destination, ce_QdbEntryCollection);
    this = (tag_collection_t*)zend_object_store_get_object(destination TSRMLS_CC);

    this->handle = handle;
    this->entries = entries;
    this->entries_count = entries_count;
    this->current = 0;
}

static inline void QdbEntryCollection_createEntry(zval* destination, qdb_handle_t handle, qdb_entry_type_t type, const char* alias TSRMLS_DC)
{
    zval *zalias;
    ALLOC_INIT_ZVAL(zalias);
    ZVAL_STRING(zalias, alias, /*dup=*/1);

    switch (type)
    {
    case qdb_entry_blob:
        QdbBlob_createInstance(destination, handle, zalias TSRMLS_CC);
        break;

    case qdb_entry_hset:
        QdbHashSet_createInstance(destination, handle, zalias TSRMLS_CC);
        break;

    case qdb_entry_integer:
        QdbInteger_createInstance(destination, handle, zalias TSRMLS_CC);
        break;

    case qdb_entry_queue:
        QdbQueue_createInstance(destination, handle, zalias TSRMLS_CC);
        break;

    case qdb_entry_tag:
        QdbTag_createInstance(destination, handle, zalias TSRMLS_CC);
        break;

    default:
        throw_bad_function_call("Entry type not supported, please update quasardb PHP API.");
        break;
    }
}

BEGIN_CLASS_METHOD_0(__destruct)
{
    qdb_free_results(this->handle, this->entries, this->entries_count);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(current) // inherited from Iterator
{
    if (this->current >= this->entries_count) return;

    const char* alias = this->entries[this->current];

    qdb_entry_type_t type;
    qdb_error_t error = qdb_get_type(this->handle, alias, &type);

    if (!error)
        QdbEntryCollection_createEntry(return_value, this->handle, type, alias TSRMLS_CC);
    else
        throw_qdb_error(error);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(key) // inherited from Iterator
{
    if (this->current >= this->entries_count) return;

    RETURN_LONG(this->current);
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(next) // inherited from Iterator
{
    this->current++;
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(rewind) // inherited from Iterator
{
    this->current = 0;
}
END_CLASS_METHOD()


BEGIN_CLASS_METHOD_0(valid) // inherited from Iterator
{
    RETURN_BOOL(this->current<this->entries_count);
}
END_CLASS_METHOD()


BEGIN_CLASS_MEMBERS()
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(current)
    ADD_METHOD(key)
    ADD_METHOD(next)
    ADD_METHOD(rewind)
    ADD_METHOD(valid)
END_CLASS_MEMBERS()

#include "class_definition.i"
