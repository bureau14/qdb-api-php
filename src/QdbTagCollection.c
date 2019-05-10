// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include "QdbTag.h"
#include "QdbTagCollection.h"
#include "class_definition.h"
#include "exceptions.h"
#include <zend_interfaces.h>
#include <qdb/client.h>

#define class_name QdbTagCollection
#define class_storage tag_collection_t
#define class_interfaces 1, zend_ce_iterator


typedef struct
{
    qdb_handle_t handle;
    const char** tags;
    size_t tags_count;
    size_t current;
} tag_collection_t;

extern zend_class_entry* ce_QdbTagCollection;


void QdbTagCollection_createInstance(
    zval* destination, qdb_handle_t handle, const char** tags, size_t tags_count)
{
    tag_collection_t* this;

    object_init_ex(destination, ce_QdbTagCollection);
    this = (tag_collection_t*)Z_OBJ_P(destination);

    this->handle = handle;
    this->tags = tags;
    this->tags_count = tags_count;
    this->current = 0;
}


CLASS_METHOD_0(__destruct)
{
    qdb_release(this->handle, this->tags);
}

CLASS_METHOD_0(current)  // inherited from Iterator
{
    if (this->current >= this->tags_count) return;

    zval alias;
    ZVAL_STRING(&alias, this->tags[this->current]);

    QdbTag_createInstance(return_value, this->handle, &alias);
}

CLASS_METHOD_0(key)  // inherited from Iterator
{
    if (this->current >= this->tags_count) return;

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
    RETURN_BOOL(this->current < this->tags_count);
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
