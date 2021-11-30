// Copyright (c) 2009-2021, quasardb SAS. All rights reserved.
// All rights reserved.

#ifndef QDB_LOG_H
#define QDB_LOG_H

#include "php_include.h"
#include <qdb/client.h>

void log_attach(void);
qdb_log_level_t log_level_from_name(const char* level);

#endif /* QDB_LOG_H */
