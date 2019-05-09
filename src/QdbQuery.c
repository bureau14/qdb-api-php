// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h

#include "QdbQuery.h"
#include "QdbQueryTable.h"
#include "QdbTimestamp.h"
#include "class_definition.h"
#include "exceptions.h"

struct zval_query_t
{
    zend_object std;
    qdb_handle_t handle;
    qdb_query_result_t* result;
    zval* tables;
    zval* scanned_point_count;
};

#define class_name QdbQuery
#define class_storage struct zval_query_t

extern zend_class_entry* ce_QdbQuery;

void QdbQuery_createInstance(zval* destination,
                             qdb_handle_t handle,
                             const char* query TSRMLS_DC)
{
    qdb_query_result_t* result = NULL;
    qdb_error_t err = qdb_query(handle, query, &result);
    if (QDB_FAILURE(err)) throw_qdb_error(err);

    object_init_ex(destination, ce_QdbQuery);
    class_storage* this = (class_storage*) zend_object_store_get_object(destination TSRMLS_CC);
    this->handle = handle;
    this->result = result;

    if (result == NULL) {
        MAKE_STD_ZVAL(this->scanned_point_count);
        ZVAL_LONG(this->scanned_point_count, 0);
        
        MAKE_STD_ZVAL(this->tables);
        array_init_size(this->tables, 0);
        return;
    }

    MAKE_STD_ZVAL(this->scanned_point_count);
	ZVAL_LONG(this->scanned_point_count, this->result->scanned_point_count);

	MAKE_STD_ZVAL(this->tables);
    array_init_size(this->tables, result->tables_count);
	for (int i = 0; i < result->tables_count; i++)
    {
        zval* table;
	    MAKE_STD_ZVAL(table);
        QdbQueryTable_createInstance(table, &result->tables[i]);
		zend_hash_next_index_insert(this->tables->value.ht, &table, sizeof(zval*), NULL);
    }
    
    php_printf("table content :\n");
    zval** ptable;
    int i = 0;
    for (zend_hash_internal_pointer_reset(src);
         zend_hash_get_current_data(src, (void**)&pcolumn) == SUCCESS;
         zend_hash_move_forward(src))
    {
        php_printf("   | %d - %s\n", (*ptable)->value.obj.handlers->get_class_entry(*ptable)->name);
    }
}

CLASS_METHOD_0(__destruct)
{
    qdb_release(this->handle, this->result);
}

CLASS_METHOD_0(tables)
{
    RETURN_ZVAL(this->tables, 0, 0);
}

CLASS_METHOD_0(scannedPointCount)
{
    RETURN_ZVAL(this->scanned_point_count, 0, 0);
}

BEGIN_CLASS_MEMBERS()
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(tables)
    ADD_METHOD(scannedPointCount)
END_CLASS_MEMBERS()

#include "class_definition.i"
