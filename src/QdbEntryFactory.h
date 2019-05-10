// Copyright (c) 2009-2016, quasardb SAS
// All rights reserved.

#ifndef QDB_ENTRY_FACTORY_H
#define QDB_ENTRY_FACTORY_H

#include <qdb/client.h>
#include <zend.h>  // include first to avoid conflict with stdint.h

void QdbEntryFactory_createFromType(
    zval* destination, qdb_handle_t handle, qdb_entry_type_t type, const char* alias);
qdb_error_t QdbEntryFactory_createFromAlias(zval* destination, qdb_handle_t handle, const char* alias);

#endif /* QDB_ENTRY_FACTORY_H */
