// Copyright (c) 2009-2015, quasardb SAS
// All rights reserved.

#ifndef QDB_LOG_H
#define QDB_LOG_H

#include <php.h> // include first to avoid conflict with stdint.h 
#include <qdb/client.h>

void log_attach(qdb_handle_t handle);
int log_level_to_int(const char* level, int def);

#endif /* QDB_LOG_H */
