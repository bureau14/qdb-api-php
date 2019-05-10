// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include "QdbQueryPoint.h"
#include "QdbQueryTable.h"
#include "QdbTimestamp.h"
#include "class_definition.h"
#include "exceptions.h"

typedef struct
{
    zval table_name;
    zval columns_names;
    zval rows;
    zval rows_count;
} _zval_query_table_t;

#define class_name QdbQueryTable
#define class_storage _zval_query_table_t

extern zend_class_entry* ce_QdbQueryTable;

void QdbQueryTable_createInstance(zval* destination, qdb_table_result_t* result)
{
    object_init_ex(destination, ce_QdbQueryTable);
    class_storage* this = (class_storage*) Z_OBJ_P(destination);

    ZVAL_STRINGL(&this->table_name, result->table_name.data, result->table_name.length);

    array_init_size(&this->columns_names, result->columns_count);
	for (int i = 0; i < result->columns_count; ++i)
    {
        zval name;
        ZVAL_STRINGL(&name, result->columns_names[i].data, result->columns_names[i].length);
		zend_hash_next_index_insert(Z_ARR(this->columns_names), &name);
    }

	ZVAL_LONG(&this->rows_count, result->rows_count);

    array_init_size(&this->rows, result->rows_count);
	for (int i = 0; i < result->rows_count; ++i)
    {
        zval row;
        array_init_size(&row, result->columns_count);

        for (int j = 0; j < result->columns_count; ++j)
        {
            zval point;
            QdbQueryPoint_createInstance(&point, &result->rows[i][j]);
            zend_hash_next_index_insert(Z_ARR(row), &point);
        }
        zend_hash_next_index_insert(Z_ARR(this->rows), &row);
    }
}

CLASS_METHOD_0(tableName)
{
    ZVAL_COPY(return_value, &this->table_name);
}

CLASS_METHOD_0(columnsNames)
{
    ZVAL_COPY(return_value, &this->columns_names);
}

CLASS_METHOD_0(rowsCount)
{
    ZVAL_COPY_VALUE(return_value, &this->rows_count);
}

CLASS_METHOD_0(pointsRows)
{
    ZVAL_COPY(return_value, &this->rows);
}

BEGIN_CLASS_MEMBERS()
    ADD_METHOD(tableName)
    ADD_METHOD(columnsNames)
    ADD_METHOD(rowsCount)
    ADD_METHOD(pointsRows)
END_CLASS_MEMBERS()

#include "class_definition.i"
