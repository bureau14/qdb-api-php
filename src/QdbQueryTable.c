// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h

#include "QdbQueryPoint.h"
#include "QdbQueryTable.h"
#include "QdbTimestamp.h"
#include "class_definition.h"
#include "exceptions.h"

struct zval_query_table_t
{
    zend_object std;
    zval* table_name;
    zval* columns_names;
    zval* rows;
    zval* rows_count;
};

#define class_name QdbQueryTable
#define class_storage struct zval_query_table_t

extern zend_class_entry* ce_QdbQueryTable;

void QdbQueryTable_createInstance(zval* destination, qdb_table_result_t* result TSRMLS_DC)
{
    object_init_ex(destination, ce_QdbQueryTable);
    class_storage* this = (class_storage*) zend_object_store_get_object(destination TSRMLS_CC);

	MAKE_STD_ZVAL(this->table_name);
    ZVAL_STRING(this->table_name, result->table_name.data, 1);

	MAKE_STD_ZVAL(this->columns_names);
    array_init_size(this->columns_names, result->columns_count);
	for (int i = 0; i < result->columns_count; ++i)
    {
        zval* name;
        MAKE_STD_ZVAL(name);
        ZVAL_STRING(name, result->columns_names[i].data, 1);
		zend_hash_next_index_insert(this->columns_names->value.ht, &name, sizeof(zval*), NULL);
    }

	MAKE_STD_ZVAL(this->rows);
    array_init_size(this->rows, result->rows_count * result->columns_count);
	for (int i = 0; i < result->rows_count; ++i)
    {
        for (int j = 0; j < result->columns_count; ++j)
        {
            zval* point;
	        MAKE_STD_ZVAL(point);
            QdbQueryPoint_createInstance(point, &result->rows[i][j]);
            zend_hash_next_index_insert(this->rows->value.ht, &point, sizeof(zval*), NULL);
        }
    }
    
	MAKE_STD_ZVAL(this->rows_count);
	ZVAL_LONG(this->rows_count, result->rows_count);
}

CLASS_METHOD_0(table_namee)
{
    RETURN_ZVAL(this->table_name, 0, 0);
}

CLASS_METHOD_0(columns_names)
{
    RETURN_ZVAL(this->columns_names, 0, 0);
}

CLASS_METHOD_0(rows_count)
{
    RETURN_ZVAL(this->rows_count, 0, 0);
}

CLASS_METHOD_2(get_point, LONG_ARG(row_index), LONG_ARG(col_index))
{
    long i = Z_LVAL_P(row_index);
    long rows_cnt = Z_LVAL_P(this->rows_count);
    if (i < 0 || i >= rows_cnt) throw_invalid_argument
        ("the row index must be between 0 and rows_count");

    long j = Z_LVAL_P(col_index);
    long columns_cnt = zend_hash_num_elements(Z_ARRVAL_P(this->columns_names));
    if (j < 0 || j >= columns_cnt) throw_invalid_argument
        ("the column index must be between 0 and columns_names size");
    
    zval* point;
    zend_hash_index_find(this->rows->value.ht, i * columns_cnt + j, (void**) &point);
    RETURN_ZVAL(point, 0, 0);
}

BEGIN_CLASS_MEMBERS()
    ADD_METHOD(table_namee)
    ADD_METHOD(columns_names)
    ADD_METHOD(rows_count)
    ADD_METHOD(get_point)
END_CLASS_MEMBERS()

#include "class_definition.i"
