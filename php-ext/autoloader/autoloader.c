/*
  +----------------------------------------------------------------------+
  | PHP Version 5                                                        |
  +----------------------------------------------------------------------+
  | Copyright (c) 1997-2013 The PHP Group                                |
  +----------------------------------------------------------------------+
  | This source file is subject to version 3.01 of the PHP license,      |
  | that is bundled with this package in the file LICENSE, and is        |
  | available through the world-wide-web at the following url:           |
  | http://www.php.net/license/3_01.txt                                  |
  | If you did not receive a copy of the PHP license and are unable to   |
  | obtain it through the world-wide-web, please send a note to          |
  | license@php.net so we can mail you a copy immediately.               |
  +----------------------------------------------------------------------+
  | Author:                                                              |
  +----------------------------------------------------------------------+
*/

/* $Id$ */

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "php.h"
#include "php_ini.h"
#include "main/SAPI.h"
#include "ext/standard/info.h"
#include "php_autoloader.h"

/* If you declare any globals in php_autoloader.h uncomment this:
ZEND_DECLARE_MODULE_GLOBALS(autoloader)
*/
ZEND_DECLARE_MODULE_GLOBALS(autoloader);

/* True global resources - no need for thread safety here */
static int le_autoloader;

/* {{{ autoloader_functions[]
 *
 * Every user visible function must have an entry in autoloader_functions[].
 */
const zend_function_entry autoloader_functions[] = {
	PHP_FE(load_class,	NULL)		/* For testing, remove later. */
	PHP_FE_END	/* Must be the last line in autoloader_functions[] */
};
/* }}} */

/* {{{ autoloader_module_entry
 */
zend_module_entry autoloader_module_entry = {
#if ZEND_MODULE_API_NO >= 20010901
	STANDARD_MODULE_HEADER,
#endif
	"autoloader",
	autoloader_functions,
	PHP_MINIT(autoloader),
	PHP_MSHUTDOWN(autoloader),
	PHP_RINIT(autoloader),		/* Replace with NULL if there's nothing to do at request start */
	PHP_RSHUTDOWN(autoloader),	/* Replace with NULL if there's nothing to do at request end */
	PHP_MINFO(autoloader),
#if ZEND_MODULE_API_NO >= 20010901
	"0.1", /* Replace with version number for your extension */
#endif
	STANDARD_MODULE_PROPERTIES
};
/* }}} */

#ifdef COMPILE_DL_AUTOLOADER
ZEND_GET_MODULE(autoloader)
#endif

/* {{{ PHP_INI
 */
/* Remove comments and fill if you need to have entries in php.ini
PHP_INI_BEGIN()
    STD_PHP_INI_ENTRY("autoloader.global_value",      "42", PHP_INI_ALL, OnUpdateLong, global_value, zend_autoloader_globals, autoloader_globals)
    STD_PHP_INI_ENTRY("autoloader.global_string", "foobar", PHP_INI_ALL, OnUpdateString, global_string, zend_autoloader_globals, autoloader_globals)
PHP_INI_END()
*/
/* }}} */

/* {{{ php_autoloader_init_globals
 */
/* Uncomment this function if you have INI entries
static void php_autoloader_init_globals(zend_autoloader_globals *autoloader_globals)
{
	autoloader_globals->global_value = 0;
	autoloader_globals->global_string = NULL;
}
*/
/* }}} */

/* {{{ PHP_MINIT_FUNCTION
 */
PHP_MINIT_FUNCTION(autoloader)
{
	/* If you have INI entries, uncomment these lines 
	REGISTER_INI_ENTRIES();
	*/
	zval *fp;
	MAKE_STD_ZVAL(fp);
	ZVAL_STRING(fp, "/opt/t-lotus.php", 1);
	ALLOC_HASHTABLE(AUTOLOADER_G(ht_class_map));
	zend_hash_init(AUTOLOADER_G(ht_class_map), 1024, NULL, ZVAL_PTR_DTOR, 1);
	zend_hash_update(AUTOLOADER_G(ht_class_map), "Lotus", sizeof("Lotus"), &fp, sizeof(zval *), NULL);
	
	zval **file;
	char *cstr;
	if (zend_hash_find(AUTOLOADER_G(ht_class_map), "Lotus", sizeof("Lotus"), (void **) &file) == SUCCESS) {
		convert_to_string(*file);
		cstr = Z_STRVAL_P(*file);
		include_file(cstr, 0 TSRMLS_CC);
	}


               
	return SUCCESS;
}
/* }}} */

/* {{{ PHP_MSHUTDOWN_FUNCTION
 */
PHP_MSHUTDOWN_FUNCTION(autoloader)
{
	/* uncomment this line if you have INI entries
	UNREGISTER_INI_ENTRIES();
	*/
	zval **file;
	char *cstr;
	if (zend_hash_find(AUTOLOADER_G(ht_class_map), "Lotus", sizeof("Lotus"), (void **) &file) == SUCCESS) {
		convert_to_string(*file);
		cstr = Z_STRVAL_P(*file);
		php_printf(cstr);
		//include_file(cstr, 0 TSRMLS_CC);
	}

	zend_hash_destroy(AUTOLOADER_G(ht_class_map));
	FREE_HASHTABLE(AUTOLOADER_G(ht_class_map));
	return SUCCESS;
}
/* }}} */

/* Remove if there's nothing to do at request start */
/* {{{ PHP_RINIT_FUNCTION
 */
PHP_RINIT_FUNCTION(autoloader)
{
	zend_fcall_info fci;
	zval *func, *date_format, *ret_ptr = NULL;
	MAKE_STD_ZVAL(func);
	ZVAL_STRING(func, "spl_autoload_register", 1); 
	zval **param[1];
	MAKE_STD_ZVAL(date_format);
	ZVAL_STRING(date_format, "load_class", 1);
	param[0] = &date_format;
	fci.size = sizeof(fci);
	fci.function_table = EG(function_table);
	fci.object_ptr = NULL;
	fci.function_name = func;
	fci.retval_ptr_ptr = &ret_ptr;
	fci.param_count = 1;
	fci.params = param;
	fci.no_separation = 0;
	fci.symbol_table = EG(active_symbol_table);
	zend_call_function(&fci, NULL TSRMLS_CC);
	efree(func);

	return SUCCESS;
}
/* }}} */
ZEND_FUNCTION(load_class)
{
	char *class_name;
	zval **file;
	uint len=0;
	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "s", &class_name, &len) == FAILURE) {
		return;
	}
	php_printf(class_name);
	if (zend_hash_find(AUTOLOADER_G(ht_class_map), class_name, sizeof(class_name), (void **) &file) == SUCCESS) {
		php_printf("test");
	//	php_printf(*file);
		//include_file(*file, 0 TSRMLS_CC);
	}
}

int include_file(char *path, int use_path TSRMLS_DC) {
	zend_file_handle file_handle;
	zend_op_array 	*op_array;
	char realpath[MAXPATHLEN];

	if (!VCWD_REALPATH(path, realpath)) {
		return 0;
	}

	file_handle.filename = path;
	file_handle.free_filename = 0;
	file_handle.type = ZEND_HANDLE_FILENAME;
	file_handle.opened_path = NULL;
	file_handle.handle.fp = NULL;

	op_array = zend_compile_file(&file_handle, ZEND_INCLUDE TSRMLS_CC);

	if (op_array && file_handle.handle.stream.handle) {
		int dummy = 1;

		if (!file_handle.opened_path) {
			file_handle.opened_path = path;
		}

		zend_hash_add(&EG(included_files), file_handle.opened_path, strlen(file_handle.opened_path)+1, (void *)&dummy, sizeof(int), NULL);
	}
	zend_destroy_file_handle(&file_handle TSRMLS_CC);

	if (op_array) {
		zval *result = NULL;


		EG(return_value_ptr_ptr) = &result;
		EG(active_op_array) 	 = op_array;

#if ((PHP_MAJOR_VERSION == 5) && (PHP_MINOR_VERSION > 2)) || (PHP_MAJOR_VERSION > 5)
		if (!EG(active_symbol_table)) {
			zend_rebuild_symbol_table(TSRMLS_C);
		}
#endif
		zend_execute(op_array TSRMLS_CC);

		destroy_op_array(op_array TSRMLS_CC);
		efree(op_array);
		if (!EG(exception)) {
			if (EG(return_value_ptr_ptr) && *EG(return_value_ptr_ptr)) {
				zval_ptr_dtor(EG(return_value_ptr_ptr));
			}
		}
	    return 1;
	}

	return 0;
}

/* Remove if there's nothing to do at request end */
/* {{{ PHP_RSHUTDOWN_FUNCTION
 */
PHP_RSHUTDOWN_FUNCTION(autoloader)
{
	return SUCCESS;
}
/* }}} */

/* {{{ PHP_MINFO_FUNCTION
 */
PHP_MINFO_FUNCTION(autoloader)
{
	php_info_print_table_start();
	php_info_print_table_header(2, "autoloader support", "enabled");
        php_info_print_table_row(2, "Version", "2.0.0");
	php_info_print_table_end();
	/* Remove comments if you have entries in php.ini
	DISPLAY_INI_ENTRIES();
	*/
}
/* }}} */


/* Remove the following function when you have successfully modified config.m4
   so that your module can be compiled into PHP, it exists only for testing
   purposes. */

/* Every user-visible function in PHP should document itself in the source */
/* {{{ proto string confirm_autoloader_compiled(string arg)
   Return a string to confirm that the module is compiled in */
PHP_FUNCTION(confirm_autoloader_compiled)
{
	char *arg = NULL;
	int arg_len, len;
	char *strg;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "s", &arg, &arg_len) == FAILURE) {
		return;
	}

	len = spprintf(&strg, 0, "Congratulations! You have successfully modified ext/%.78s/config.m4. Module %.78s is now compiled into PHP.", "autoloader", arg);
	RETURN_STRINGL(strg, len, 0);
}
/* }}} */
/* The previous line is meant for vim and emacs, so it can correctly fold and 
   unfold functions in source code. See the corresponding marks just before 
   function definition, where the functions purpose is also documented. Please 
   follow this convention for the convenience of others editing your code.
*/


/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: noet sw=4 ts=4 fdm=marker
 * vim<600: noet sw=4 ts=4
 */
