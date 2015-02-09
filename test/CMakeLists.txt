set(BOOTSTRAP "${CMAKE_CURRENT_SOURCE_DIR}/bootstrap.php")
set(PHPUNIT "${CMAKE_CURRENT_SOURCE_DIR}/../../../../thirdparty/phpunit/phpunit.phar")

if(WIN32)
    set(OUTPUT_DIR "${LIBRARY_OUTPUT_PATH}/${CMAKE_BUILD_TYPE}")
    add_test(
        NAME qdb_php_test
        COMMAND "${PHP_BUILD_DIR}/php.exe" "-dextension_dir=${OUTPUT_DIR}" "-dextension=php_qdb.dll" "${PHPUNIT}" --bootstrap "${BOOTSTRAP}" "${CMAKE_CURRENT_SOURCE_DIR}"
        WORKING_DIRECTORY "${OUTPUT_DIR}"
    )
    add_test(
        NAME qdb_php_ts_test
        COMMAND "${PHPTS_BUILD_DIR}/php.exe" "-dextension_dir=${OUTPUT_DIR}" "-dextension=php_qdb_ts.dll" "${PHPUNIT}" --bootstrap "${BOOTSTRAP}" "${CMAKE_CURRENT_SOURCE_DIR}"
        WORKING_DIRECTORY "${OUTPUT_DIR}"
    )
else()
    add_test(
        NAME qdb_php_test
        COMMAND php "-dextension=${PHP_QDB_EXT}" "${PHPUNIT}" --bootstrap "${BOOTSTRAP}" "${CMAKE_CURRENT_SOURCE_DIR}"
        WORKING_DIRECTORY ${EXECUTABLE_OUTPUT_PATH}
    )
    set_property(TEST qdb_php_test PROPERTY
        ENVIRONMENT "LD_LIBRARY_PATH=${LIBRARY_OUTPUT_PATH}"
    )
endif()
