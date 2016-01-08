// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include <php.h>  // include first to avoid conflict with stdint.h

#include "connection.h"
#include "exceptions.h"
#include "globals.h"
#include "log.h"

static void handle_dtor(void* arg)
{
    qdb_close((qdb_handle_t)arg);
}

void connection_init(TSRMLS_D)
{
    zend_hash_init(&QDB_G(connections), 0, NULL, handle_dtor, 1);
}

void connection_shutdown(TSRMLS_D)
{
    zend_hash_destroy(&QDB_G(connections));
}

static void connection_save(zval* uri, qdb_handle_t handle TSRMLS_DC)
{
    zend_hash_update(&QDB_G(connections), Z_STRVAL_P(uri), Z_STRLEN_P(uri) + 1, &handle, sizeof(handle), NULL);
}

static qdb_handle_t connection_load(zval* uri TSRMLS_DC)
{
    qdb_handle_t* handle_ptr = NULL;

    zend_hash_find(&QDB_G(connections), Z_STRVAL_P(uri), Z_STRLEN_P(uri) + 1, (void**)&handle_ptr);

    return handle_ptr ? *handle_ptr : NULL;
}

qdb_handle_t connection_open(zval* uri TSRMLS_DC)
{
    if (QDB_G(persistent))
    {
        qdb_handle_t handle = connection_load(uri TSRMLS_CC);
        if (handle)
            return handle;
    }

    log_attach();

    qdb_handle_t handle = qdb_open_tcp();

    qdb_error_t error = qdb_connect(handle, Z_STRVAL_P(uri));

    if (error == qdb_e_invalid_argument)
        throw_invalid_argument("Cluster URI is invalid.");
    else if (error)
        throw_qdb_error(error);
    else if (QDB_G(persistent))
        connection_save(uri, handle TSRMLS_CC);

    return handle;
}

void connection_close(qdb_handle_t handle TSRMLS_DC)
{
    if (!QDB_G(persistent))
        qdb_close(handle);
}