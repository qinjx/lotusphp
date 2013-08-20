dnl $Id$
dnl config.m4 for extension autoloader

dnl Comments in this file start with the string 'dnl'.
dnl Remove where necessary. This file will not work
dnl without editing.

dnl If your extension references something external, use with:

dnl PHP_ARG_WITH(autoloader, for autoloader support,
dnl Make sure that the comment is aligned:
dnl [  --with-autoloader             Include autoloader support])

dnl Otherwise use enable:

PHP_ARG_ENABLE(autoloader, whether to enable autoloader support,
dnl Make sure that the comment is aligned:
[  --enable-autoloader           Enable autoloader support])

if test "$PHP_AUTOLOADER" != "no"; then
  dnl Write more examples of tests here...

  dnl # --with-autoloader -> check with-path
  dnl SEARCH_PATH="/usr/local /usr"     # you might want to change this
  dnl SEARCH_FOR="/include/autoloader.h"  # you most likely want to change this
  dnl if test -r $PHP_AUTOLOADER/$SEARCH_FOR; then # path given as parameter
  dnl   AUTOLOADER_DIR=$PHP_AUTOLOADER
  dnl else # search default path list
  dnl   AC_MSG_CHECKING([for autoloader files in default path])
  dnl   for i in $SEARCH_PATH ; do
  dnl     if test -r $i/$SEARCH_FOR; then
  dnl       AUTOLOADER_DIR=$i
  dnl       AC_MSG_RESULT(found in $i)
  dnl     fi
  dnl   done
  dnl fi
  dnl
  dnl if test -z "$AUTOLOADER_DIR"; then
  dnl   AC_MSG_RESULT([not found])
  dnl   AC_MSG_ERROR([Please reinstall the autoloader distribution])
  dnl fi

  dnl # --with-autoloader -> add include path
  dnl PHP_ADD_INCLUDE($AUTOLOADER_DIR/include)

  dnl # --with-autoloader -> check for lib and symbol presence
  dnl LIBNAME=autoloader # you may want to change this
  dnl LIBSYMBOL=autoloader # you most likely want to change this 

  dnl PHP_CHECK_LIBRARY($LIBNAME,$LIBSYMBOL,
  dnl [
  dnl   PHP_ADD_LIBRARY_WITH_PATH($LIBNAME, $AUTOLOADER_DIR/lib, AUTOLOADER_SHARED_LIBADD)
  dnl   AC_DEFINE(HAVE_AUTOLOADERLIB,1,[ ])
  dnl ],[
  dnl   AC_MSG_ERROR([wrong autoloader lib version or lib not found])
  dnl ],[
  dnl   -L$AUTOLOADER_DIR/lib -lm
  dnl ])
  dnl
  dnl PHP_SUBST(AUTOLOADER_SHARED_LIBADD)

  PHP_NEW_EXTENSION(autoloader, autoloader.c, $ext_shared)
fi
