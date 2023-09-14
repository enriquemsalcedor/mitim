<?php

/*
 * WARNING!
 *
 * Do not use this index.php as an entry point on production.
 *
 * Instead, set your website document root to /dist directory.
 *
 */

define('APP_ENV', 'production');
define('APP_PUBLIC_PATH', 'dist/');

require 'dist/incidentestemp.php';