// Copyright (c) 2009-2023, quasardb SAS. All rights reserved.
// All rights reserved.

#ifndef QDB_CONNECTION
#define QDB_CONNECTION

#include "php_include.h"
#include <qdb/client.h>

void connection_init();
void connection_shutdown();

qdb_handle_t connection_open(zval* uri);
void connection_close(qdb_handle_t handle);

#endif
