// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#include "connection.h"
#include "exceptions.h"
#include "globals.h"
#include "log.h"

static void handle_dtor(zval* arg)
{
    qdb_close((qdb_handle_t) arg->value.ptr);
    zval_ptr_dtor(arg);
}

void connection_init()
{
    zend_hash_init(&QDB_G(connections), 0, NULL, handle_dtor, 1);
}

void connection_shutdown()
{
    zend_hash_destroy(&QDB_G(connections));
}

static void connection_save(zval* uri, qdb_handle_t handle)
{
	zval hstorage;
	ZVAL_PTR(&hstorage);
    Z_PTR(hstorage) = handle;

    zend_hash_update(&QDB_G(connections), Z_STR_P(uri), &hstorage);
}

static qdb_handle_t connection_load(zval* uri)
{
    zval* hstorage = zend_hash_find(&QDB_G(connections), Z_STR_P(uri));
    return hstorage ? (qdb_handle_t) hstorage->value.ptr : NULL;
}

qdb_handle_t connection_open(zval* uri)
{
    if (QDB_G(persistent))
    {
        qdb_handle_t handle = connection_load(uri);
        if (handle) return handle;
    }

    log_attach();

    qdb_handle_t handle = qdb_open_tcp();

    qdb_error_t error = qdb_connect(handle, Z_STRVAL_P(uri));

    if (error == qdb_e_invalid_argument)
        throw_invalid_argument("Cluster URI is invalid.");
    else if (error)
        throw_qdb_error(error);
    else if (QDB_G(persistent))
        connection_save(uri, handle);

    return handle;
}

void connection_close(qdb_handle_t handle)
{
    if (!QDB_G(persistent)) qdb_close(handle);
}
