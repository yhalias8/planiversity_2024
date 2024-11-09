<?php

if (!function_exists('getRootPath')) {
	function getRootPath()
	{
		$currentUrl = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

		if (strpos($currentUrl, 'staging') !== false) {
			$tmp = '/staging/';
		} else {
			$tmp = '/';
		};

		return $_SERVER['DOCUMENT_ROOT'] . $tmp;
	}
}

if (!function_exists('isProduction')) {
	function isProduction()
	{
		$currentUrl = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

		return strpos($currentUrl, 'https://www.planiversity.com') && !strpos($currentUrl, 'staging');
	}
}

