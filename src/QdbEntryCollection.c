// Copyright (c) 2009-2020, quasardb SAS. All rights reserved.
// All rights reserved.

#include "QdbEntryCollection.h"
#include "QdbEntryFactory.h"
#include "class_definition.h"
#include "exceptions.h"
#include <zend_interfaces.h>
#include <qdb/client.h>

#define class_name QdbEntryCollection
#define class_storage tag_collection_t
#define class_interfaces 1, zend_ce_iterator


typedef struct
{
    qdb_handle_t handle;
    const char** entries;
    size_t entries_count;
    size_t current;
} tag_collection_t;

extern zend_class_entry* ce_QdbEntryCollection;


void QdbEntryCollection_createInstance(
    zval* destination, qdb_handle_t handle, const char** entries, size_t entries_count)
{
    object_init_ex(destination, ce_QdbEntryCollection);
    tag_collection_t* this = get_class_storage(destination);

    this->handle = handle;
    this->entries = entries;
    this->entries_count = entries_count;
    this->current = 0;
}

CLASS_METHOD_0(__destruct)
{
    qdb_release(this->handle, this->entries);
}

CLASS_METHOD_0(current)  // inherited from Iterator
{
    if (this->current >= this->entries_count) return;

    const char* alias = this->entries[this->current];
    qdb_error_t error = QdbEntryFactory_createFromAlias(return_value, this->handle, alias);

    if (error) throw_qdb_error(error);
}

CLASS_METHOD_0(key)  // inherited from Iterator
{
    if (this->current >= this->entries_count) return;

    RETURN_LONG(this->current);
}

CLASS_METHOD_0(next)  // inherited from Iterator
{
    this->current++;
}

CLASS_METHOD_0(rewind)  // inherited from Iterator
{
    this->current = 0;
}

CLASS_METHOD_0(valid)  // inherited from Iterator
{
    RETURN_BOOL(this->current < this->entries_count);
}

BEGIN_CLASS_MEMBERS()
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(current)
    ADD_METHOD(key)
    ADD_METHOD(next)
    ADD_METHOD(rewind)
    ADD_METHOD(valid)
END_CLASS_MEMBERS()

#include "class_definition.i"
