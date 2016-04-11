<?php
/**
 * Created by PhpStorm.
 * User: Tamal
 * Date: 2/16/16
 * Time: 1:36 PM
 */
ob_start();

session_start();
//session_destroy();

defined("DS") ? null : define("DS", DIRECTORY_SEPARATOR);


defined("TEMPLATE_FRONT") ? null : define("TEMPLATE_FRONT", __DIR__ . DS . "templates/front" );

defined("TEMPLATE_BACK") ? null : define("TEMPLATE_BACK", __DIR__ . DS . "templates/back" );

defined("UPLOAD_DIRECTORY") ? null : define("UPLOAD_DIRECTORY", __DIR__ . DS . "uploads" );

defined("DB_HOST") ? null : define("DB_HOST", "localhost" );

defined("DB_USER") ? null : define("DB_USER", "root" );

defined("DB_PASS") ? null : define("DB_PASS", "mysql");

defined("DB_NAME") ? null : define("DB_NAME", "ecommerce" );

defined("TEMPLATE_BACK") ? null : define("TEMPLATE_BACK", __DIR__ . DS . "templates/back" );

$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);

require_once("cart.php");
require_once("functions.php");
