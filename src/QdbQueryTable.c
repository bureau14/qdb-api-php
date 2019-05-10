// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

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

void QdbQueryTable_createInstance(zval* destination, qdb_table_result_t* result)
{
    object_init_ex(destination, ce_QdbQueryTable);
    class_storage* this = (class_storage*) Z_OBJ_P(destination);

	MAKE_STD_ZVAL(this->table_name);
    ZVAL_STRINGL(this->table_name, result->table_name.data, result->table_name.length);

	MAKE_STD_ZVAL(this->columns_names);
    array_init_size(this->columns_names, result->columns_count);
	for (int i = 0; i < result->columns_count; ++i)
    {
        zval* name;
        MAKE_STD_ZVAL(name);
        ZVAL_STRINGL(name, result->columns_names[i].data, result->columns_names[i].length);
		zend_hash_next_index_insert(this->columns_names->value.ht, &name, sizeof(zval*), NULL);
    }

	MAKE_STD_ZVAL(this->rows_count);
	ZVAL_LONG(this->rows_count, result->rows_count);

	MAKE_STD_ZVAL(this->rows);
    array_init_size(this->rows, result->rows_count);
	for (int i = 0; i < result->rows_count; ++i)
    {
        zval* row;
        MAKE_STD_ZVAL(row);
        array_init_size(row, result->columns_count);
        zend_hash_next_index_insert(this->rows->value.ht, &row, sizeof(zval*), NULL);

        for (int j = 0; j < result->columns_count; ++j)
        {
            zval* point;
	        MAKE_STD_ZVAL(point);
            QdbQueryPoint_createInstance(point, &result->rows[i][j]);
            zend_hash_next_index_insert(row->value.ht, &point, sizeof(zval*), NULL);
        }
    }
}

CLASS_METHOD_0(table_name)
{
    Z_ADDREF_P(this->table_name);
    *return_value = *this->table_name;
}

CLASS_METHOD_0(columns_names)
{
    Z_ADDREF_P(this->columns_names);
    *return_value = *this->columns_names;
}

CLASS_METHOD_0(rows_count)
{
    Z_ADDREF_P(this->rows_count);
    *return_value = *this->rows_count;
}

CLASS_METHOD_0(points_rows)
{
    Z_ADDREF_P(this->rows);
    *return_value = *this->rows;
}

BEGIN_CLASS_MEMBERS()
    ADD_METHOD(table_name)
    ADD_METHOD(columns_names)
    ADD_METHOD(rows_count)
    ADD_METHOD(points_rows)
END_CLASS_MEMBERS()

#include "class_definition.i"
