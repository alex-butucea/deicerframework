<?php
/**
 * Deicer Test Bootstrap
 *
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

// Set error reporting to the level to which Deicer Framework code must comply
error_reporting(E_ALL | E_STRICT);

// Setup include path
$rootPath = realpath(dirname(__DIR__));
$paths = array (
    $rootPath,
    $rootPath . '/library',
    $rootPath . '/tests',
);
set_include_path(implode(PATH_SEPARATOR, $paths) . PATH_SEPARATOR . get_include_path());

// Invoke Composer generated autoloader
require __DIR__ . '/../vendor/autoload.php';
