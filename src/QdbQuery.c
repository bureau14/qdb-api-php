// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include "QdbQuery.h"
#include "QdbQueryTable.h"
#include "QdbTimestamp.h"
#include "class_definition.h"
#include "exceptions.h"

struct zval_query_t
{
    qdb_handle_t handle;
    qdb_query_result_t* result;
    zval tables;
    zval scanned_point_count;
};

#define class_name QdbQuery
#define class_storage struct zval_query_t

extern zend_class_entry* ce_QdbQuery;

void QdbQuery_createInstance(zval* destination,
                             qdb_handle_t handle,
                             const char* query)
{
    qdb_query_result_t* result = NULL;
    qdb_error_t err = qdb_query(handle, query, &result);
    if (QDB_FAILURE(err)) throw_qdb_error(err);

    object_init_ex(destination, ce_QdbQuery);
    class_storage* this = (class_storage*) Z_OBJ_P(destination);
    this->handle = handle;
    this->result = result;

    if (result == NULL) {
        ZVAL_LONG(&this->scanned_point_count, 0);
        array_init_size(&this->tables, 0);
        return;
    }

	ZVAL_LONG(&this->scanned_point_count, this->result->scanned_point_count);
    array_init_size(&this->tables, result->tables_count);

    HashTable* tables_ht = Z_ARR(this->tables);
	for (int i = 0; i < result->tables_count; i++)
    {
        zval table;
        QdbQueryTable_createInstance(&table, &result->tables[i]);
		zend_hash_next_index_insert(tables_ht, &table);
    }
}

CLASS_METHOD_0(__destruct)
{
    qdb_release(this->handle, this->result);
}

CLASS_METHOD_0(tables)
{
    ZVAL_COPY(return_value, &this->tables);
}

CLASS_METHOD_0(scannedPointCount)
{
    ZVAL_COPY_VALUE(return_value, &this->scanned_point_count);
}

BEGIN_CLASS_MEMBERS()
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(tables)
    ADD_METHOD(scannedPointCount)
END_CLASS_MEMBERS()

#include "class_definition.i"
