// Copyright (c) 2009-2019, quasardb SAS
// All rights reserved.

#include "QdbQuery.h"
#include "QdbTimestamp.h"
#include "class_definition.h"
#include "exceptions.h"

typedef struct
{
    qdb_handle_t handle;
    qdb_query_result_t* result;
    zval column_names;
    zval rows;
    zval scanned_point_count;
} _zval_query_t;

#define class_name QdbQuery
#define class_storage _zval_query_t

extern zend_class_entry* ce_QdbQuery;

void QdbQuery_createInstance(zval* destination,
                             qdb_handle_t handle,
                             const char* query)
{
    qdb_query_result_t* result = NULL;
    qdb_error_t err = qdb_query(handle, query, &result);

    if (QDB_FAILURE(err)) {
        qdb_release(handle, result);
        throw_qdb_error(err);
    }

    object_init_ex(destination, ce_QdbQuery);
    class_storage* this = get_class_storage(destination);
    this->handle = handle;
    this->result = result;

    if (result == NULL) {
        array_init_size(&this->column_names, 0);
        array_init_size(&this->rows, 0);
        ZVAL_LONG(&this->scanned_point_count, 0);
        return;
    }

    // Colum names.
    array_init_size(&this->column_names, result->column_count);
	for (int i = 0; i < result->column_count; ++i)
    {
        zval name;
        ZVAL_STRINGL(&name, result->column_names[i].data, result->column_names[i].length);
		zend_hash_next_index_insert(Z_ARR(this->column_names), &name);
    }

    // Rows.
    array_init_size(&this->rows, result->row_count);
	for (int i = 0; i < result->row_count; ++i)
    {
        zval row;
        array_init_size(&row, result->column_count);

        for (int j = 0; j < result->column_count; ++j)
        {
            zval point;
            QdbQueryPoint_createInstance(&point, &result->rows[i][j]);
            zend_hash_next_index_insert(Z_ARR(row), &point);
        }
        zend_hash_next_index_insert(Z_ARR(this->rows), &row);
    }
    
	ZVAL_LONG(&this->scanned_point_count, result->scanned_point_count);
}

CLASS_METHOD_0(__destruct)
{
    qdb_release(this->handle, this->result);
}

CLASS_METHOD_0(columnNames)
{
    ZVAL_COPY(return_value, &this->column_names);
}

CLASS_METHOD_0(rows)
{
    ZVAL_COPY(return_value, &this->rows);
}

CLASS_METHOD_0(scannedPointCount)
{
    ZVAL_COPY_VALUE(return_value, &this->scanned_point_count);
}

BEGIN_CLASS_MEMBERS()
    ADD_DESTRUCTOR(__destruct)
    ADD_METHOD(columnNames)
    ADD_METHOD(rows)
    ADD_METHOD(scannedPointCount)
END_CLASS_MEMBERS()

#include "class_definition.i"
