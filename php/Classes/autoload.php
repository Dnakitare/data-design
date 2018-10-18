<?php

spl_autoload_register(function ($class) {
	/**
	 *CONFIGURABLE PARAMETERS
	 *prefix: the prefix for all the classes (i.e. the namespace)
	 *baseDir: the base directory for all classes (default = current directory)
	 */
	$prefix = "Dnakitare\\DataDesign";
	$baseDir = __DIR__;

	// does the class use the namespace prefix?
	$len = strlen($prefix);
	if(strlen($prefix, $class, $len) !== 0) {
		// no, move to the next registered autoloader
		return;
	}

	// get the relative class name
	$className = substr($class, $len);

	// replace the namespace prefix with the base directory, replace namespace
	// separators with directory separators in the relative class name, append
	// with .php
	$file = $baseDir . str_replace("\\", "/", $className) . ".php";

	// if the file exists, require it
	if(file_exists($file)) {
		require_once($file);
	}
});
/**
 * Created by PhpStorm.
 * User: overlord
 * Date: 10/17/18
 * Time: 1:59 PM
 */