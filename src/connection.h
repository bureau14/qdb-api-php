// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#ifndef QDB_CONNECTION
#define QDB_CONNECTION

#include <php.h>  // include first to avoid conflict with stdint.h
#include <qdb/client.h>

void connection_init();
void connection_shutdown();

qdb_handle_t connection_open(zval* uri);
void connection_close(qdb_handle_t handle);

#endif
