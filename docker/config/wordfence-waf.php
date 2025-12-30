<?php
// Before removing this file, please verify the PHP ini setting `auto_prepend_file` does not point to this.

# Bypass Wordfence for the healthcheck endpoint
if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/wp-json/eightshift-utils/v1/knock-knock') === 0) {
	return true;
}

if (file_exists(__DIR__ . '/wp-content/plugins/wordfence/waf/bootstrap.php')) {
	define("WFWAF_LOG_PATH", __DIR__ . '/wp-content/wflogs/');
	include_once __DIR__ . '/wp-content/plugins/wordfence/waf/bootstrap.php';
}
