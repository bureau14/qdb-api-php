SWIG_GENERATE(qdb php qdb.i ${CMAKE_CURRENT_SOURCE_DIR})
    
install(FILES 
    php_qdb.h 
    qdb.php
    qdb_php_wrapper.c
    DESTINATION php)
