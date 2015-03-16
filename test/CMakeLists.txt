set(BOOTSTRAP "${CMAKE_CURRENT_SOURCE_DIR}/bootstrap.php")
set(PHPUNIT "${CMAKE_CURRENT_SOURCE_DIR}/../../../../thirdparty/phpunit/phpunit.phar")

if(WIN32)
    add_test(
        NAME qdb_php_test
        COMMAND "${PHP_BUILD_DIR}/php.exe" "-dextension_dir=${QDB_OUTPUT_DIRECTORY}" "-dextension=php_qdb.dll" "${PHPUNIT}" --bootstrap "${BOOTSTRAP}" "${CMAKE_CURRENT_SOURCE_DIR}"
        WORKING_DIRECTORY "${QDB_OUTPUT_DIRECTORY}"
    )
    add_test(
        NAME qdb_php_ts_test
        COMMAND "${PHPTS_BUILD_DIR}/php.exe" "-dextension_dir=${QDB_OUTPUT_DIRECTORY}" "-dextension=php_qdb_ts.dll" "${PHPUNIT}" --bootstrap "${BOOTSTRAP}" "${CMAKE_CURRENT_SOURCE_DIR}"
        WORKING_DIRECTORY "${QDB_OUTPUT_DIRECTORY}"
    )
else()
    add_test(
        NAME qdb_php_test
        COMMAND php "-dextension=${PHP_QDB_EXT}" "${PHPUNIT}" --bootstrap "${BOOTSTRAP}" "${CMAKE_CURRENT_SOURCE_DIR}"
        WORKING_DIRECTORY "${QDB_OUTPUT_DIRECTORY}"
    )
    set_property(TEST qdb_php_test PROPERTY
        ENVIRONMENT "LD_LIBRARY_PATH=${QDB_OUTPUT_DIRECTORY}"
    )
endif()
